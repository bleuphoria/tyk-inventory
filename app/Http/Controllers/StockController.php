<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function create()
    {
        $products = Product::all();
        return view('stock.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Create transaction (this triggers the observer to log activity)
            InventoryTransaction::create($validated);

            // 2. Update product stock
            $product = Product::find($validated['product_id']);
            if ($validated['type'] === 'in') {
                $product->increment('stock_quantity', $validated['quantity']);
            } else {
                $product->decrement('stock_quantity', $validated['quantity']);
            }
        });

        return redirect()->route('dashboard')->with('success', 'Stock updated successfully.');
    }
}
