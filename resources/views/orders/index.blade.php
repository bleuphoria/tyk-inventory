@extends('layouts.app')

@section('title', 'Sales Orders & Invoices')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Sales Orders & Receipts</h1>
            <p class="text-sm text-slate-500 mt-1">Customer invoices, POS transactions, and retail sales history</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.orders_pdf') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition">
                <i class="fa-solid fa-file-pdf text-rose-500"></i>
                <span>Export PDF</span>
            </a>
            <a href="{{ route('orders.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-600/20 transition-all">
                <i class="fa-solid fa-cash-register"></i>
                <span>New Sale (POS)</span>
            </a>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Sales Revenue</span>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">
                {{ currency_symbol() }} {{ number_format($totalRevenue, 2) }}
            </div>
            <span class="text-xs text-emerald-600 font-semibold mt-2 flex items-center gap-1">
                <i class="fa-solid fa-circle-check text-[10px]"></i> Completed POS orders
            </span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Orders Processed</span>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">
                {{ number_format($totalOrdersCount) }}
            </div>
            <span class="text-xs text-slate-400 font-semibold mt-2 block">Customer invoices logged</span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Average Order Value</span>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">
                {{ currency_symbol() }} {{ $totalOrdersCount > 0 ? number_format($totalRevenue / $totalOrdersCount, 2) : '0.00' }}
            </div>
            <span class="text-xs text-blue-600 font-semibold mt-2 block">Mean ticket size</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('orders.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by invoice #, customer name, phone..."
                       class="w-full pl-9 pr-3 py-2 text-xs font-medium border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div class="w-full sm:w-56">
                <select name="payment_method" class="w-full py-2 px-3 text-xs font-medium border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-slate-700 bg-white">
                    <option value="">All Payment Methods</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash (₱)</option>
                    <option value="gcash" {{ request('payment_method') == 'gcash' ? 'selected' : '' }}>GCash / Maya</option>
                    <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-5 rounded-xl text-xs font-bold transition">
                    Filter Orders
                </button>
                @if(request()->hasAny(['search', 'payment_method']))
                    <a href="{{ route('orders.index') }}" class="bg-slate-100 text-slate-600 py-2 px-3 rounded-xl text-xs hover:bg-slate-200 transition flex items-center justify-center font-bold" title="Clear filters">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Invoice #</th>
                        <th class="py-3.5 px-5">Date & Time</th>
                        <th class="py-3.5 px-5">Customer</th>
                        <th class="py-3.5 px-5 text-center">Items</th>
                        <th class="py-3.5 px-5">Payment</th>
                        <th class="py-3.5 px-5 text-right">Total Amount</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-4 px-5 font-mono font-bold text-blue-600 text-sm">
                            <a href="{{ route('orders.show', $order) }}" class="hover:underline">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="py-4 px-5 text-slate-500 whitespace-nowrap">
                            <div class="font-bold text-slate-800">{{ $order->created_at->format('M d, Y') }}</div>
                            <div class="text-[11px] text-slate-400">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="py-4 px-5">
                            <div class="font-bold text-slate-900 text-sm">{{ $order->customer_name }}</div>
                            @if($order->customer_phone)
                                <div class="text-[11px] text-slate-400 font-mono">{{ $order->customer_phone }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-center">
                            <span class="bg-slate-100 text-slate-700 text-xs px-2.5 py-1 rounded-full font-bold">
                                {{ $order->items->sum('quantity') }}
                            </span>
                        </td>
                        <td class="py-4 px-5">
                            <span class="capitalize text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700">
                                {{ $order->payment_method }}
                            </span>
                        </td>
                        <td class="py-4 px-5 text-right text-sm font-black text-slate-900 whitespace-nowrap">
                            {{ currency_symbol() }} {{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="py-4 px-5 text-right whitespace-nowrap">
                            <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                <i class="fa-solid fa-receipt"></i>
                                <span>Invoice</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-receipt text-4xl mb-3 block text-slate-300"></i>
                            <span class="font-semibold text-slate-600">No sales orders found.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
