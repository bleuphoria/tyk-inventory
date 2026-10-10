@extends('layouts.app')

@section('title', 'Replenishment & Alerts Hub')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Replenishment & Shortage Center</h1>
            <p class="text-sm text-slate-500 mt-1">Review depleted inventory and low stock items below threshold ({{ low_stock_threshold() }} units)</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('suppliers.index') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition">
                <i class="fa-solid fa-truck-ramp-box text-blue-600"></i>
                <span>Supplier Directory</span>
            </a>
            <a href="{{ route('stock.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-600/20 transition-all">
                <i class="fa-solid fa-boxes-packing"></i>
                <span>Restock Inventory</span>
            </a>
        </div>
    </div>

    <!-- Healthy Status Banner (if no shortages) -->
    @if($outOfStock->isEmpty() && $lowStock->isEmpty())
    <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm">
        <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto text-2xl mb-4 shadow-inner">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h2 class="text-xl font-black text-slate-800">All Stock Levels Healthy</h2>
        <p class="text-slate-400 text-sm mt-1.5 max-w-md mx-auto">There are currently no items out of stock or below the critical threshold ({{ low_stock_threshold() }} units).</p>
        <div class="mt-6">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-bold text-xs transition">
                <i class="fa-solid fa-boxes-stacked"></i> Browse Full Inventory Catalog
            </a>
        </div>
    </div>
    @endif

    <!-- Critical: Out of Stock (0 units) -->
    @if($outOfStock->isNotEmpty())
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <span class="w-3 h-3 rounded-full bg-rose-500 animate-ping"></span>
            <h2 class="text-lg font-black text-rose-700">Critical: Out of Stock ({{ $outOfStock->count() }})</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($outOfStock as $product)
            <div class="bg-white p-6 rounded-3xl border-l-4 border-l-rose-500 border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-bold text-slate-800 text-base leading-tight">{{ $product->name }}</h3>
                            <div class="text-[11px] font-mono text-slate-400 mt-0.5">SKU: {{ $product->sku }}</div>
                        </div>
                        <span class="bg-rose-100 text-rose-700 text-xs px-2.5 py-1 rounded-full font-black">
                            0 Units
                        </span>
                    </div>

                    <!-- Supplier Details -->
                    <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-600 space-y-1">
                        @if($product->supplier)
                            <div class="font-bold text-slate-700 flex items-center gap-1.5">
                                <i class="fa-solid fa-truck text-slate-400"></i> {{ $product->supplier->name }}
                            </div>
                            @if($product->supplier->phone)
                                <div class="text-slate-500 pl-5 text-[11px]">Phone: {{ $product->supplier->phone }}</div>
                            @endif
                        @else
                            <div class="text-slate-400 italic text-[11px]">No supplier assigned</div>
                        @endif
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-600">{{ currency_symbol() }} {{ number_format($product->price, 2) }}</span>
                    <a href="{{ route('stock.create', ['product_id' => $product->id, 'type' => 'in']) }}"
                       class="inline-flex items-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs px-3.5 py-2 rounded-xl font-bold transition shadow-sm shadow-rose-600/20">
                        <i class="fa-solid fa-boxes-packing"></i> Quick Restock
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Low Stock (Below Threshold) -->
    @if($lowStock->isNotEmpty())
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
            <h2 class="text-lg font-black text-amber-800">Low Stock Warnings ({{ $lowStock->count() }})</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($lowStock as $product)
            <div class="bg-white p-6 rounded-3xl border-l-4 border-l-amber-400 border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-bold text-slate-800 text-base leading-tight">{{ $product->name }}</h3>
                            <div class="text-[11px] font-mono text-slate-400 mt-0.5">SKU: {{ $product->sku }}</div>
                        </div>
                        <span class="bg-amber-100 text-amber-800 text-xs px-2.5 py-1 rounded-full font-black">
                            {{ $product->stock_quantity }} Left
                        </span>
                    </div>

                    <!-- Supplier Details -->
                    <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-600 space-y-1">
                        @if($product->supplier)
                            <div class="font-bold text-slate-700 flex items-center gap-1.5">
                                <i class="fa-solid fa-truck text-slate-400"></i> {{ $product->supplier->name }}
                            </div>
                            @if($product->supplier->phone)
                                <div class="text-slate-500 pl-5 text-[11px]">Phone: {{ $product->supplier->phone }}</div>
                            @endif
                        @else
                            <div class="text-slate-400 italic text-[11px]">No supplier assigned</div>
                        @endif
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-600">{{ currency_symbol() }} {{ number_format($product->price, 2) }}</span>
                    <a href="{{ route('stock.create', ['product_id' => $product->id, 'type' => 'in']) }}"
                       class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs px-3.5 py-2 rounded-xl font-bold transition shadow-sm shadow-amber-500/20">
                        <i class="fa-solid fa-arrow-down"></i> Add Stock
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
