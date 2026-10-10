@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Page Title & Live Date -->
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard</h1>
        <p class="text-xs font-semibold text-slate-400 mt-0.5">{{ date('l, F j, Y') }}</p>
    </div>

    <!-- Top Action Required Alert Banner -->
    @if(($lowStockItemsCount + $outOfStockCount) > 0)
    <div class="bg-amber-500 text-white p-3.5 sm:p-4 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-sm">
        <div class="flex items-center gap-3">
            <span class="bg-amber-600/90 text-white text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                ACTION REQUIRED
            </span>
            <span class="text-xs font-bold text-amber-50">
                {{ $outOfStockCount > 0 ? "{$outOfStockCount} Out of Stock" : '' }} 
                {{ $outOfStockCount > 0 && $lowStockItemsCount > 0 ? '•' : '' }}
                {{ $lowStockItemsCount > 0 ? "{$lowStockItemsCount} Low Stock Items" : '' }}
            </span>
        </div>
        <a href="{{ route('alerts.index') }}" class="bg-white text-slate-900 hover:bg-slate-50 px-4 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm shrink-0">
            <span>Manage Inventory</span>
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>
    @endif

    <!-- 5 Top Metric Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        
        <!-- Today's Revenue -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Today's Revenue</span>
                <div class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-[10px]">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono mt-2 truncate">
                {{ currency_symbol() }}{{ number_format($todayRevenue, 2) }}
            </div>
            <div class="flex items-center gap-2 mt-2">
                <span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded flex items-center gap-0.5">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 100%
                </span>
                <span class="text-[10px] text-slate-400 font-medium">{{ $todayTxnsCount }} {{ Str::plural('txn', $todayTxnsCount) }}</span>
            </div>
        </div>

        <!-- Today's Profit -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Today's Profit</span>
                <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-[10px]">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono mt-2 truncate">
                {{ currency_symbol() }}{{ number_format($todayProfit, 2) }}
            </div>
            <div class="flex items-center gap-2 mt-2">
                <span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded">
                    {{ $todayMargin }}% M
                </span>
                <span class="text-[10px] text-slate-400 font-medium">net gain</span>
            </div>
        </div>

        <!-- Monthly Profit -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Monthly Profit</span>
                <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-[10px]">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono mt-2 truncate">
                {{ currency_symbol() }}{{ number_format($monthlyProfit, 2) }}
            </div>
            <div class="flex items-center gap-2 mt-2">
                <span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded">
                    {{ $monthlyMargin }}% M
                </span>
                <span class="text-[10px] text-slate-400 font-medium">30 days</span>
            </div>
        </div>

        <!-- Inventory Value -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Inventory Value</span>
                <div class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-[10px]">
                    <i class="fa-solid fa-store"></i>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono mt-2 truncate">
                {{ currency_symbol() }}{{ number_format($totalStockValue, 2) }}
            </div>
            <div class="mt-2 text-[10px] text-slate-400 font-medium">
                {{ $totalDistinctProducts }} {{ Str::plural('product', $totalDistinctProducts) }} ({{ number_format($totalStockUnits) }} units)
            </div>
        </div>

        <!-- Cash Drawer -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between col-span-2 md:col-span-1">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Cash Drawer</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500 mt-1"></span>
            </div>
            <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono mt-2 truncate">
                {{ currency_symbol() }}{{ number_format($todayRevenue, 2) }}
            </div>
            <div class="mt-2 text-[10px] text-slate-400 font-medium flex items-center gap-1">
                <span class="text-emerald-600 font-bold">Active Shift</span> • POS Ready
            </div>
        </div>

    </div>

    <!-- Middle Row: Financial Trend Chart & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Financial Trend Line Chart (Left 8 Cols) -->
        <div class="lg:col-span-8 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <h2 class="text-base font-black text-slate-900">Financial Trend</h2>
                <div class="flex items-center gap-1 bg-slate-50 p-1 rounded-xl border border-slate-200/60 text-xs">
                    <button type="button" class="px-3 py-1 rounded-lg font-bold bg-white text-slate-900 shadow-xs">7 Days</button>
                    <button type="button" class="px-3 py-1 rounded-lg font-semibold text-slate-400 hover:text-slate-700">30 Days</button>
                    <button type="button" class="px-3 py-1 rounded-lg font-semibold text-slate-400 hover:text-slate-700">12 Months</button>
                </div>
            </div>

            <div class="h-64 w-full relative">
                <canvas id="financialTrendChart"></canvas>
            </div>

            <div class="flex items-center justify-center gap-6 pt-2 border-t border-slate-100 text-xs font-semibold">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    <span class="text-slate-600">Revenue</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-slate-600">Profit</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <span class="text-slate-600">Expenses</span>
                </div>
            </div>
        </div>

        <!-- Top Products Leaderboard (Right 4 Cols) -->
        <div class="lg:col-span-4 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-cube"></i>
                    </div>
                    <h2 class="text-base font-black text-slate-900">Top Products</h2>
                </div>
                <a href="{{ route('products.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View All</a>
            </div>

            <div class="space-y-4 flex-1">
                @forelse($topProducts as $idx => $item)
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="font-bold text-slate-400 w-4 text-center">{{ $idx + 1 }}</span>
                            <span class="font-bold text-slate-800 truncate">{{ $item->product->name ?? 'Product' }}</span>
                        </div>
                        <span class="font-bold text-slate-900 font-mono shrink-0 ml-2">
                            {{ currency_symbol() }}{{ number_format($item->product->price ?? 0, 2) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400 pl-6">
                        <span>{{ $item->total_sold ?? 0 }} sold</span>
                    </div>
                    <div class="pl-6 w-full">
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min(100, max(20, 100 - ($idx * 25))) }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-xs text-slate-400">
                    No sales recorded yet.
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Bottom Row: 3 Fast Action & Insight Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Slow Movers / High Stock -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-900">Slow Movers</h3>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Inventory</span>
            </div>

            <div class="space-y-2.5">
                @forelse($slowMovers as $p)
                <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition text-xs">
                    <div class="min-w-0 flex-1 pr-2">
                        <div class="font-bold text-slate-800 truncate">{{ $p->name }}</div>
                        <div class="text-[10px] text-slate-400 font-mono">SKU: {{ $p->sku }}</div>
                    </div>
                    <span class="font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-md text-[10px] shrink-0">
                        {{ $p->stock_quantity }} units
                    </span>
                </div>
                @empty
                <div class="text-center py-4 text-xs text-slate-400">No data available.</div>
                @endforelse
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-900">Low Stock</h3>
                </div>
                <a href="{{ route('alerts.index') }}" class="text-[10px] font-bold text-blue-600 hover:underline">View All</a>
            </div>

            <div class="space-y-2.5">
                @forelse($lowStockItems as $p)
                <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition text-xs">
                    <div class="min-w-0 flex-1 pr-2">
                        <div class="font-bold text-slate-800 truncate">{{ $p->name }}</div>
                        <div class="text-[10px] text-slate-400 font-mono">SKU: {{ $p->sku }}</div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="font-black text-amber-700 bg-amber-100/70 px-2 py-0.5 rounded-md text-[10px] block">
                            {{ $p->stock_quantity }} left
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-xs text-emerald-600 font-semibold flex items-center justify-center gap-1">
                    <i class="fa-solid fa-circle-check text-xs"></i> All stock levels healthy
                </div>
                @endforelse
            </div>
        </div>

        <!-- Critical / Depleted Stock Items -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-boxes-packing"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-900">Depleted Stock</h3>
                </div>
                <a href="{{ route('stock.create') }}" class="text-[10px] font-bold text-blue-600 hover:underline">Restock</a>
            </div>

            <div class="space-y-2.5">
                @forelse($outOfStockList as $p)
                <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition text-xs">
                    <div class="min-w-0 flex-1 pr-2">
                        <div class="font-bold text-slate-800 truncate">{{ $p->name }}</div>
                        <div class="text-[10px] text-slate-400 font-mono">SKU: {{ $p->sku }}</div>
                    </div>
                    <span class="font-black text-rose-700 bg-rose-100 px-2 py-0.5 rounded-md text-[10px] shrink-0">
                        0 units
                    </span>
                </div>
                @empty
                <div class="text-center py-4 text-xs text-emerald-600 font-semibold flex items-center justify-center gap-1">
                    <i class="fa-solid fa-circle-check text-xs"></i> No depleted items
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const trendCtx = document.getElementById('financialTrendChart');
    if (trendCtx) {
        new Chart(trendCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($trendLabels) !!},
                datasets: [
                    {
                        label: 'Revenue',
                        data: {!! json_encode($trendRevenue) !!},
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        tension: 0.35,
                        fill: true,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#2563eb',
                    },
                    {
                        label: 'Profit',
                        data: {!! json_encode($trendProfit) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        tension: 0.35,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#10b981',
                    },
                    {
                        label: 'Expenses',
                        data: {!! json_encode($trendExpenses) !!},
                        borderColor: '#f43f5e',
                        backgroundColor: 'transparent',
                        tension: 0.35,
                        borderDash: [4, 4],
                        borderWidth: 1.5,
                        pointRadius: 0,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: '600' }, color: '#94a3b8' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { size: 10 }, color: '#94a3b8' }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection
