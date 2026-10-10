@extends('layouts.app')

@section('title', 'Reports & PDF Exports')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Reports & PDF Hub</h1>
            <p class="text-sm text-slate-500 mt-1">Generate official PDF documents and export CSV datasets for accounting and stock auditing</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Stock Summary Report -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl mb-4 shadow-inner">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <h3 class="text-base font-black text-slate-900">Inventory Valuation & Stock</h3>
                <p class="text-slate-500 text-xs mt-2 leading-relaxed">
                    Complete list of products, available stock levels, SKU codes, supplier details, and total inventory value calculation in ₱.
                </p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
                <a href="{{ route('reports.stock_summary_pdf') }}" class="w-full inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm shadow-rose-600/20 transition">
                    <i class="fa-solid fa-file-pdf"></i> Download PDF Report
                </a>
                <a href="{{ route('reports.stock_summary') }}" class="w-full inline-flex items-center justify-center gap-2 text-slate-400 hover:text-slate-700 text-xs font-semibold py-1 transition">
                    <i class="fa-solid fa-file-csv"></i> Export as CSV
                </a>
            </div>
        </div>

        <!-- Stock Transactions History Report -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-4 shadow-inner">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h3 class="text-base font-black text-slate-900">Stock Movements Ledger</h3>
                <p class="text-slate-500 text-xs mt-2 leading-relaxed">
                    Detailed audit log of all Stock In (deliveries) and Stock Out (deductions) events with reference numbers and date stamps.
                </p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
                <a href="{{ route('reports.transactions_pdf') }}" class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm shadow-blue-600/20 transition">
                    <i class="fa-solid fa-file-pdf"></i> Download PDF Report
                </a>
                <a href="{{ route('reports.transactions_summary') }}" class="w-full inline-flex items-center justify-center gap-2 text-slate-400 hover:text-slate-700 text-xs font-semibold py-1 transition">
                    <i class="fa-solid fa-file-csv"></i> Export as CSV
                </a>
            </div>
        </div>

        <!-- Sales Orders Export -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-4 shadow-inner">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h3 class="text-base font-black text-slate-900">Customer Sales Orders</h3>
                <p class="text-slate-500 text-xs mt-2 leading-relaxed">
                    Comprehensive log of POS transactions, customer names, payment types (Cash/GCash/Card), and daily gross revenue.
                </p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
                <a href="{{ route('reports.orders_pdf') }}" class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm shadow-emerald-600/20 transition">
                    <i class="fa-solid fa-file-pdf"></i> Download PDF Report
                </a>
                <a href="{{ route('reports.orders_summary') }}" class="w-full inline-flex items-center justify-center gap-2 text-slate-400 hover:text-slate-700 text-xs font-semibold py-1 transition">
                    <i class="fa-solid fa-file-csv"></i> Export as CSV
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
