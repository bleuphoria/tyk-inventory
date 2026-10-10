@extends('layouts.app')

@section('title', 'Sales & Financial Analytics')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Financial & Sales Analytics</h1>
            <p class="text-sm text-slate-500 mt-1">Real-time revenue, estimated margins, category performance, and top-selling bikes & components</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('orders.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-600/20 transition-all">
                <i class="fa-solid fa-cash-register"></i>
                <span>New Sale</span>
            </a>
        </div>
    </div>

    <!-- 4 Financial Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Gross Revenue -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Gross Revenue</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-peso-sign font-bold"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-3 font-mono">
                {{ currency_symbol() }} {{ number_format($grossRevenue, 2) }}
            </div>
            <span class="text-xs text-slate-400 mt-2 font-medium">{{ $ordersCount }} sales orders completed</span>
        </div>

        <!-- Cost of Goods Sold -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Inventory Cost (COGS)</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-3 font-mono">
                {{ currency_symbol() }} {{ number_format($cogs, 2) }}
            </div>
            <span class="text-xs text-slate-400 mt-2 font-medium">Cost of goods sold basis</span>
        </div>

        <!-- Net Profit -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Estimated Net Profit</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-emerald-600 mt-3 font-mono">
                {{ currency_symbol() }} {{ number_format($netProfit, 2) }}
            </div>
            <span class="text-xs text-emerald-700 font-semibold mt-2 flex items-center gap-1">
                <i class="fa-solid fa-arrow-up text-[10px]"></i> Gross Revenue − Stock Cost
            </span>
        </div>

        <!-- Profit Margin % -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Profit Margin</span>
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-percent"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-purple-600 mt-3 font-mono">
                {{ $profitMargin }}%
            </div>
            <span class="text-xs text-slate-400 mt-2 font-medium">Gross margin profitability</span>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Category Revenue Share Donut -->
        <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-black text-slate-900 text-base">Revenue by Category</h2>
                    <p class="text-slate-400 text-xs mt-0.5">Share of sales across bikes, components & accessories</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center relative min-h-[260px]">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Payment Method Distribution -->
        <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-black text-slate-900 text-base">Payment Method Distribution</h2>
                    <p class="text-slate-400 text-xs mt-0.5">Cash vs GCash / Maya vs Cards breakdown</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center relative min-h-[260px]">
                <canvas id="paymentChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Top Performing Products Leaderboard -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100">
            <div>
                <h2 class="font-black text-slate-900 text-lg">Top-Selling Bikes & Parts</h2>
                <p class="text-slate-400 text-xs mt-0.5">Ranked by unit sales volume and generated retail revenue</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 text-xs font-bold">
                Browse Full Catalog &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 pb-3">
                        <th class="pb-3">Rank</th>
                        <th class="pb-3">Product Name</th>
                        <th class="pb-3">Category</th>
                        <th class="pb-3 text-center">Units Sold</th>
                        <th class="pb-3 text-right">Revenue Generated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($topProducts as $idx => $item)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-4">
                            <span class="w-6 h-6 rounded-full {{ $idx === 0 ? 'bg-amber-100 text-amber-800 font-black' : ($idx === 1 ? 'bg-slate-200 text-slate-800' : 'bg-slate-100 text-slate-500') }} inline-flex items-center justify-center text-xs font-bold">
                                {{ $idx + 1 }}
                            </span>
                        </td>
                        <td class="py-4">
                            <div class="font-bold text-slate-900 text-sm">{{ $item->product->name ?? 'Product' }}</div>
                            <span class="text-[11px] text-slate-400 font-mono">SKU: {{ $item->product->sku ?? 'N/A' }}</span>
                        </td>
                        <td class="py-4">
                            <span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-full font-bold">
                                {{ $item->product->category->name ?? 'General' }}
                            </span>
                        </td>
                        <td class="py-4 text-center font-black text-slate-800">
                            {{ number_format($item->total_sold) }}
                        </td>
                        <td class="py-4 text-right font-black text-slate-900 font-mono text-sm whitespace-nowrap">
                            {{ currency_symbol() }} {{ number_format($item->total_revenue, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            No sales records available yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    // Category Revenue Chart
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categoryLabels) !!},
            datasets: [{
                data: {!! json_encode($categoryValues) !!},
                backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'],
                borderWidth: 2,
                borderColor: '#ffffff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11, weight: '600' }, padding: 14 }
                }
            }
        }
    });

    // Payment Method Chart
    const payCtx = document.getElementById('paymentChart').getContext('2d');
    new Chart(payCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($paymentLabels) !!},
            datasets: [{
                label: 'Orders',
                data: {!! json_encode($paymentCounts) !!},
                backgroundColor: '#2563eb',
                borderRadius: 10,
                barPercentage: 0.45,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { weight: '600', size: 11 } } },
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { stepSize: 1, font: { weight: '600' } } }
            }
        }
    });
</script>
@endpush
@endsection
