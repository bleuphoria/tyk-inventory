<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items.product')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $orders = $query->paginate(15)->withQueryString();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $totalOrdersCount = Order::count();

        return view('orders.index', compact('orders', 'totalRevenue', 'totalOrdersCount'));
    }

    public function create()
    {
        $products = Product::with(['category', 'supplier'])
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'payment_method' => 'required|in:cash,card,gcash,bank_transfer',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $order = DB::transaction(function () use ($request) {
                $orderNumber = 'TYK-' . date('Ymd') . '-' . strtoupper(Str::random(4));
                $totalAmount = 0;
                $itemsToCreate = [];

                // Validate stock and calculate totals
                foreach ($request->items as $itemData) {
                    $product = Product::lockForUpdate()->find($itemData['product_id']);

                    if ($product->stock_quantity < $itemData['quantity']) {
                        throw new \Exception("Insufficient stock for '{$product->name}'. Available: {$product->stock_quantity}, Requested: {$itemData['quantity']}");
                    }

                    $unitPrice = $product->price;
                    $subtotal = $unitPrice * $itemData['quantity'];
                    $totalAmount += $subtotal;

                    $itemsToCreate[] = [
                        'product' => $product,
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ];
                }

                // Create Order
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'payment_method' => $request->payment_method,
                    'status' => 'completed',
                    'total_amount' => $totalAmount,
                    'notes' => $request->notes,
                ]);

                // Create Items, adjust stock & record InventoryTransactions
                foreach ($itemsToCreate as $entry) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $entry['product']->id,
                        'quantity' => $entry['quantity'],
                        'unit_price' => $entry['unit_price'],
                        'subtotal' => $entry['subtotal'],
                    ]);

                    // Deduct stock
                    $entry['product']->decrement('stock_quantity', $entry['quantity']);

                    // Create stock out transaction (Observer will automatically log activity)
                    InventoryTransaction::create([
                        'product_id' => $entry['product']->id,
                        'type' => 'out',
                        'quantity' => $entry['quantity'],
                        'reference' => $orderNumber,
                        'notes' => "Sale to {$order->customer_name}",
                    ]);
                }

                return $order;
            });

            return redirect()->route('orders.show', $order)->with('success', "Order #{$order->order_number} successfully placed!");

        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $order->load(['items.product.category']);
        return view('orders.show', compact('order'));
    }

    public function downloadPdf(Order $order)
    {
        $order->load(['items.product.category']);
        $pdf = Pdf::loadView('reports.pdf.invoice', compact('order'));
        return $pdf->download('invoice_' . $order->order_number . '.pdf');
    }
}
