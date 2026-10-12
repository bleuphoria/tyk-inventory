@extends('layouts.app')

@section('title', 'Products Catalog')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <!-- Page Header & Primary Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Products & Inventory Items</h2>
            <p class="text-xs text-gray-500 mt-1">Manage bicycles, spare components, and accessories with live stock tracking.</p>
        </div>

        @if(Auth::user()->isAdmin() || Auth::user()->isInventoryManager())
        <div class="flex items-center gap-3">
            <a href="{{ route('stock.create') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-right-arrow-left"></i> Adjust Stock
            </a>
            <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-blue-500/25 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add New Product
            </a>
        </div>
        @endif
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-8 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products by name, model, or SKU identifier..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white text-xs font-medium transition">
            </div>

            <div class="sm:col-span-4 flex items-center gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter"></i> Search & Filter
                </button>
                @if(request('search'))
                    <a href="{{ route('products.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 p-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Clear Filter">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Data Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-500 uppercase tracking-wider text-[10px] border-b border-gray-100">
                    <tr>
                        <th class="py-4 px-6 font-bold">Product / SKU</th>
                        <th class="py-4 px-6 font-bold">Category</th>
                        <th class="py-4 px-6 font-bold">Supplier</th>
                        <th class="py-4 px-6 font-bold text-right">Retail Price</th>
                        <th class="py-4 px-6 font-bold text-right">Unit Cost</th>
                        <th class="py-4 px-6 font-bold text-center">Stock Level</th>
                        <th class="py-4 px-6 font-bold text-center">Status</th>
                        @if(Auth::user()->isAdmin() || Auth::user()->isInventoryManager())
                        <th class="py-4 px-6 font-bold text-right">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/70 transition group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                @if($product->image_url)
                                    <a href="{{ $product->image_url }}" target="_blank" class="flex-shrink-0" title="Click to view full image">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-11 h-11 object-cover rounded-xl border border-gray-200/80 shadow-sm hover:opacity-90 transition">
                                    </a>
                                @else
                                    <div class="w-11 h-11 rounded-xl bg-gray-100 border border-gray-200/60 flex items-center justify-center text-gray-400 flex-shrink-0">
                                        <i class="fa-solid fa-bicycle text-sm text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="font-bold text-gray-900 text-sm truncate">{{ $product->name }}</div>
                                    <div class="text-[11px] font-mono text-gray-400 mt-0.5">SKU: {{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-gray-600">
                            @if($product->supplier)
                                <span class="font-medium text-gray-800">{{ $product->supplier->name }}</span>
                            @else
                                <span class="text-gray-400 italic">No supplier</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right font-black text-gray-900 text-sm">
                            {{ currency_symbol() }}{{ number_format($product->price, 2) }}
                        </td>
                        <td class="py-4 px-6 text-right font-medium text-gray-500">
                            {{ currency_symbol() }}{{ number_format($product->cost, 2) }}
                        </td>
                        <td class="py-4 px-6 text-center font-black text-sm {{ $product->stock_quantity === 0 ? 'text-rose-600' : ($product->stock_quantity <= low_stock_threshold() ? 'text-amber-600' : 'text-gray-800') }}">
                            {{ number_format($product->stock_quantity) }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($product->stock_quantity === 0)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Out of Stock
                                </span>
                            @elseif($product->stock_quantity <= low_stock_threshold())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Low Stock
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> In Stock
                                </span>
                            @endif
                        </td>

                        @if(Auth::user()->isAdmin() || Auth::user()->isInventoryManager())
                        <td class="py-4 px-6 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('products.edit', $product) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Product">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Product">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center text-gray-400">
                            <div class="w-16 h-16 rounded-3xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                            <div class="font-bold text-gray-700 text-base">No Products Found</div>
                            <p class="text-xs text-gray-400 mt-1">Try adjusting your search filters or register a new product.</p>
                            @if(Auth::user()->isAdmin() || Auth::user()->isInventoryManager())
                            <a href="{{ route('products.create') }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 hover:bg-blue-700 transition">
                                <i class="fa-solid fa-plus mr-1"></i> Add Product
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if($products->hasPages())
        <div class="p-4 sm:p-6 border-t border-gray-100 bg-gray-50/50">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
