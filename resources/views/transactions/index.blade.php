@extends('layouts.app')

@section('title', 'Stock Movements Ledger')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Stock Movements Ledger</h1>
            <p class="text-sm text-slate-500 mt-1">Full immutable audit trail of stock-in shipments, adjustments, and inventory deductions</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.transactions_pdf') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition">
                <i class="fa-solid fa-file-pdf text-rose-500"></i>
                <span>Export PDF</span>
            </a>
            <a href="{{ route('stock.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-600/20 transition-all">
                <i class="fa-solid fa-plus"></i>
                <span>Record Movement</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            <!-- Search -->
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ref, notes, SKU..."
                       class="w-full pl-9 pr-3 py-2 text-xs font-medium border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <!-- Movement Type Filter -->
            <div>
                <select name="type" class="w-full py-2 px-3 text-xs font-medium border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-slate-700 bg-white">
                    <option value="">All Movement Types</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In (+) — Shipments</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out (-) — Deductions</option>
                </select>
            </div>

            <!-- Product Filter -->
            <div>
                <select name="product_id" class="w-full py-2 px-3 text-xs font-medium border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-slate-700 bg-white">
                    <option value="">All Products ({{ $products->count() }})</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-xl text-xs font-bold transition">
                    Filter Ledger
                </button>
                @if(request()->hasAny(['search', 'type', 'product_id']))
                    <a href="{{ route('transactions.index') }}" class="bg-slate-100 text-slate-600 py-2 px-3 rounded-xl text-xs hover:bg-slate-200 transition flex items-center justify-center font-bold" title="Clear filters">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Ledger Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Timestamp</th>
                        <th class="py-3.5 px-5">Product & SKU</th>
                        <th class="py-3.5 px-5">Type</th>
                        <th class="py-3.5 px-5 text-center">Quantity Delta</th>
                        <th class="py-3.5 px-5">Reference Code</th>
                        <th class="py-3.5 px-5">Audit Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-4 px-5 text-slate-500 whitespace-nowrap">
                            <div class="font-bold text-slate-800">{{ $tx->created_at->format('M d, Y') }}</div>
                            <div class="text-[11px] text-slate-400">{{ $tx->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="py-4 px-5">
                            <div class="font-bold text-slate-900 text-sm">{{ $tx->product->name ?? 'Deleted Product' }}</div>
                            <div class="text-[11px] font-mono text-slate-400">SKU: {{ $tx->product->sku ?? 'N/A' }}</div>
                        </td>
                        <td class="py-4 px-5">
                            @if($tx->type === 'in')
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-bold">
                                    <i class="fa-solid fa-arrow-down text-[10px]"></i> Stock In
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 text-xs px-2.5 py-1 rounded-full font-bold">
                                    <i class="fa-solid fa-arrow-up text-[10px]"></i> Stock Out
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-center font-black text-sm {{ $tx->type === 'in' ? 'text-emerald-600' : 'text-purple-600' }}">
                            {{ $tx->type === 'in' ? '+' : '-' }}{{ number_format($tx->quantity) }}
                        </td>
                        <td class="py-4 px-5 font-mono text-slate-600">
                            {{ $tx->reference ?: '—' }}
                        </td>
                        <td class="py-4 px-5 text-slate-500 max-w-xs truncate">
                            {{ $tx->notes ?: '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-clock-rotate-left text-4xl mb-3 block text-slate-300"></i>
                            <span class="font-semibold text-slate-600">No stock movement logs found.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
