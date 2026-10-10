@extends('layouts.app')

@section('title', 'Invoice ' . $order->order_number)

@push('styles')
<style>
    @media print {
        header, aside, #mobile-sidebar-backdrop, .no-print, #toast-container {
            display: none !important;
        }
        main {
            padding: 0 !important;
            margin: 0 !important;
            background: white !important;
        }
        body {
            background: white !important;
        }
        .print-card {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            max-width: 100% !important;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back & Actions Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 no-print">
        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 text-xs font-bold transition">
            <i class="fa-solid fa-arrow-left"></i> Back to Orders
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('orders.create') }}" class="inline-flex items-center gap-1.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition">
                <i class="fa-solid fa-plus"></i> New Sale
            </a>
            <a href="{{ route('orders.pdf', $order) }}" class="inline-flex items-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm shadow-rose-600/20 transition">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm shadow-blue-600/20 transition">
                <i class="fa-solid fa-print"></i> Print Invoice
            </button>
        </div>
    </div>

    <!-- Official Invoice Card -->
    <div class="bg-white p-8 sm:p-12 rounded-3xl border border-slate-200/80 shadow-sm print-card space-y-8">

        <!-- Invoice Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-8 border-b border-slate-100 gap-6">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">{{ shop_name() }}</span>
                    <span class="text-[10px] bg-slate-900 text-white px-2 py-0.5 rounded font-black uppercase tracking-wider">OFFICIAL INVOICE</span>
                </div>
                <p class="text-xs text-slate-500 mt-1.5">{{ setting('shop_address') }}</p>
                <p class="text-xs text-slate-500">Phone: {{ setting('shop_phone') }} • Email: {{ setting('shop_email') }}</p>
                <p class="text-[11px] text-slate-400 font-mono mt-0.5">TIN: {{ setting('shop_tin') }}</p>
            </div>
            <div class="sm:text-right">
                <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-slate-400">INVOICE NUMBER</div>
                <div class="text-xl font-mono font-black text-slate-900 mt-0.5">{{ $order->order_number }}</div>
                <div class="text-xs text-slate-400 mt-1">{{ $order->created_at->format('F d, Y — h:i A') }}</div>
            </div>
        </div>

        <!-- Customer & Order Meta -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 py-6 border-b border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">BILLED TO</span>
                <strong class="text-slate-900 text-sm block">{{ $order->customer_name }}</strong>
                @if($order->customer_phone)
                    <span class="text-slate-500 font-mono">{{ $order->customer_phone }}</span>
                @endif
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">PAYMENT METHOD</span>
                <span class="uppercase font-bold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg inline-block">
                    {{ $order->payment_method }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">ORDER STATUS</span>
                <span class="font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-lg inline-block capitalize">
                    {{ $order->status }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">TOTAL DUE</span>
                <strong class="text-blue-600 text-base font-black">{{ currency_symbol() }} {{ number_format($order->total_amount, 2) }}</strong>
            </div>
        </div>

        <!-- Itemized Table -->
        <div class="py-4">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 pb-3">
                        <th class="pb-3 font-semibold">Item Description</th>
                        <th class="pb-3 text-center font-semibold">Qty</th>
                        <th class="pb-3 text-right font-semibold">Unit Price</th>
                        <th class="pb-3 text-right font-semibold">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="py-4">
                            <div class="font-bold text-slate-900 text-sm">{{ $item->product->name ?? 'Product' }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">
                                SKU: {{ $item->product->sku ?? 'N/A' }}
                                @if(isset($item->product->category))
                                    • {{ $item->product->category->name }}
                                @endif
                            </div>
                        </td>
                        <td class="py-4 text-center text-slate-700 font-black">{{ $item->quantity }}</td>
                        <td class="py-4 text-right text-slate-600 font-mono">{{ currency_symbol() }} {{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-4 text-right font-black text-slate-900 font-mono">{{ currency_symbol() }} {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals & Notes -->
        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-between gap-6">
            <div class="text-xs text-slate-500 max-w-sm space-y-3">
                @if($order->notes)
                    <div>
                        <div class="font-bold text-slate-700 mb-1">Notes / Instructions:</div>
                        <p class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-slate-600">{{ $order->notes }}</p>
                    </div>
                @endif
                <div class="text-slate-400 leading-relaxed">
                    {{ setting('receipt_footer') }}
                </div>
            </div>

            <div class="w-full sm:w-72 space-y-2 text-xs">
                <div class="flex justify-between text-slate-500">
                    <span>Subtotal:</span>
                    <span class="font-mono font-bold">{{ currency_symbol() }} {{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>VAT ({{ setting('tax_rate', '12') }}% - {{ strtoupper(setting('tax_mode', 'inclusive')) }}):</span>
                    <span class="font-mono">Included</span>
                </div>
                <div class="flex justify-between text-base font-black text-slate-900 pt-3 border-t border-slate-200">
                    <span>Grand Total:</span>
                    <span class="text-blue-600 font-mono">{{ currency_symbol() }} {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
