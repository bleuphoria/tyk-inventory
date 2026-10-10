<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ActivityLog;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $threshold = low_stock_threshold();

        // 1. Top Metrics
        $todayOrders = Order::where('status', 'completed')->whereDate('created_at', $today);
        $todayRevenue = (float)($todayOrders->sum('total_amount') ?? 0);
        $todayTxnsCount = $todayOrders->count();

        // Today's Profit (approximate from order items and cost)
        $todayCogs = (float)(OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereDate('orders.created_at', $today)
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->sum(DB::raw('order_items.quantity * products.cost')) ?? 0);
        $todayProfit = max(0, $todayRevenue - $todayCogs);
        $todayMargin = $todayRevenue > 0 ? round(($todayProfit / $todayRevenue) * 100, 1) : 0;

        // Monthly Profit
        $monthlyRevenue = (float)(Order::where('status', 'completed')->where('created_at', '>=', $thisMonth)->sum('total_amount') ?? 0);
        $monthlyCogs = (float)(OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->where('orders.created_at', '>=', $thisMonth)
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->sum(DB::raw('order_items.quantity * products.cost')) ?? 0);
        $monthlyProfit = max(0, $monthlyRevenue - $monthlyCogs);
        $monthlyMargin = $monthlyRevenue > 0 ? round(($monthlyProfit / $monthlyRevenue) * 100, 1) : 0;

        // Inventory Valuation & Counts
        $totalStockUnits = (int)(Product::sum('stock_quantity') ?? 0);
        $totalDistinctProducts = Product::count();
        $totalStockValue = (float)(Product::selectRaw('SUM(price * stock_quantity) as total_val')->value('total_val') ?? 0);

        // Low Stock & Out of Stock counts & lists
        $lowStockItems = Product::where('stock_quantity', '<=', $threshold)->where('stock_quantity', '>', 0)->take(5)->get();
        $lowStockItemsCount = Product::where('stock_quantity', '<=', $threshold)->where('stock_quantity', '>', 0)->count();
        $outOfStockList = Product::where('stock_quantity', '<=', 0)->take(5)->get();
        $outOfStockCount = Product::where('stock_quantity', '<=', 0)->count();

        // Slow Movers (items with highest stock or 0 recent sales)
        $slowMovers = Product::orderBy('stock_quantity', 'desc')->take(4)->get();

        // Top Selling Products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(5)
            ->get();

        // Fallback if no order items yet: use products with high price/stock
        if ($topProducts->isEmpty()) {
            $topProducts = Product::orderByDesc('price')->take(4)->get()->map(function($p) {
                return (object)[
                    'product' => $p,
                    'total_sold' => 0,
                    'total_revenue' => $p->price
                ];
            });
        }

        // 7-Day Financial Trend Data (Optimized into 2 grouped queries)
        $startDate = Carbon::today()->subDays(6)->startOfDay();

        $dailyRevenues = Order::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('CAST(created_at AS DATE) as day_key, SUM(total_amount) as total_rev')
            ->groupBy('day_key')
            ->pluck('total_rev', 'day_key');

        $dailyCosts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'completed')
            ->where('orders.created_at', '>=', $startDate)
            ->selectRaw('CAST(orders.created_at AS DATE) as day_key, SUM(order_items.quantity * products.cost) as total_cost')
            ->groupBy('day_key')
            ->pluck('total_cost', 'day_key');

        $trendLabels = [];
        $trendRevenue = [];
        $trendProfit = [];
        $trendExpenses = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayKey = $date->format('Y-m-d');
            $trendLabels[] = $date->format('D');

            $dayRev = (float)($dailyRevenues[$dayKey] ?? 0);
            $dayCost = (float)($dailyCosts[$dayKey] ?? 0);
            $dayProf = max(0, $dayRev - $dayCost);

            $trendRevenue[] = $dayRev;
            $trendProfit[] = $dayProf;
            $trendExpenses[] = $dayCost;
        }

        // Recent Audit Activities
        $recentActivities = ActivityLog::latest()->take(5)->get();

        return view('dashboard', compact(
            'todayRevenue', 'todayTxnsCount', 'todayProfit', 'todayMargin',
            'monthlyRevenue', 'monthlyProfit', 'monthlyMargin',
            'totalStockUnits', 'totalDistinctProducts', 'totalStockValue',
            'lowStockItemsCount', 'lowStockItems',
            'outOfStockCount', 'outOfStockList',
            'slowMovers', 'topProducts',
            'trendLabels', 'trendRevenue', 'trendProfit', 'trendExpenses',
            'recentActivities'
        ));
    }
}
