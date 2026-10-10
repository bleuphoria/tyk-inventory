<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        $outOfStock = Product::with(['category', 'supplier'])
            ->where('stock_quantity', 0)
            ->latest()
            ->get();

        $lowStock = Product::with(['category', 'supplier'])
            ->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 20)
            ->orderBy('stock_quantity', 'asc')
            ->get();

        return view('alerts.index', compact('outOfStock', 'lowStock'));
    }
}
