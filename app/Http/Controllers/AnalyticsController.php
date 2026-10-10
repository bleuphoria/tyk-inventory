<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $ordersCount = Order::where('status', 'completed')->count();
        $grossRevenue = (float) Order::where('status', 'completed')->sum('total_amount');

        // If no retail orders exist yet, derive metrics from stock-out transactions
        if ($grossRevenue == 0) {
            $grossRevenue = (float) InventoryTransaction::where('type', 'out')
                ->join('products', 'inventory_transactions.product_id', '=', 'products.id')
                ->sum(DB::raw('inventory_transactions.quantity * products.price'));

            $cogs = (float) InventoryTransaction::where('type', 'out')
                ->join('products', 'inventory_transactions.product_id', '=', 'products.id')
                ->sum(DB::raw('inventory_transactions.quantity * products.cost'));
        } else {
            $cogs = (float) OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
                ->sum(DB::raw('order_items.quantity * products.cost'));
        }

        $netProfit = max(0, $grossRevenue - $cogs);
        $profitMargin = $grossRevenue > 0 ? round(($netProfit / $grossRevenue) * 100, 1) : 0;

        // Top Selling Products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->with('product.category')
            ->get();

        // If no order items yet, fallback to top products with highest sales transactions
        if ($topProducts->isEmpty()) {
            $topProducts = InventoryTransaction::where('type', 'out')
                ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('product_id')
                ->orderByDesc('total_sold')
                ->take(5)
                ->with('product.category')
                ->get()
                ->map(function ($item) {
                    $item->total_revenue = $item->total_sold * ($item->product->price ?? 0);
                    return $item;
                });
        }

        // Category Performance Breakdown
        $categories = Category::with('products')->get();
        $categoryLabels = [];
        $categoryValues = [];
        foreach ($categories as $cat) {
            $catRevenue = OrderItem::whereHas('product', function ($q) use ($cat) {
                $q->where('category_id', $cat->id);
            })->sum('subtotal');

            if ($catRevenue == 0) {
                $catRevenue = InventoryTransaction::where('type', 'out')
                    ->whereHas('product', function ($q) use ($cat) {
                        $q->where('category_id', $cat->id);
                    })
                    ->join('products', 'inventory_transactions.product_id', '=', 'products.id')
                    ->sum(DB::raw('inventory_transactions.quantity * products.price'));
            }

            $categoryLabels[] = $cat->name;
            $categoryValues[] = (float) $catRevenue;
        }

        // Payment Method Breakdown
        $payments = Order::select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        $paymentLabels = $payments->pluck('payment_method')->map(fn($p) => strtoupper($p))->toArray();
        $paymentCounts = $payments->pluck('count')->toArray();

        if (empty($paymentLabels)) {
            $paymentLabels = ['CASH', 'GCASH', 'CARD'];
            $paymentCounts = [1, 0, 0];
        }

        return view('analytics.index', compact(
            'ordersCount',
            'grossRevenue',
            'cogs',
            'netProfit',
            'profitMargin',
            'topProducts',
            'categoryLabels',
            'categoryValues',
            'paymentLabels',
            'paymentCounts'
        ));
    }
}
