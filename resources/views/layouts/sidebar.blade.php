@php
    $currentUser = Auth::user();
    $lowStockThreshold = low_stock_threshold();
    $shortageCount = \Illuminate\Support\Facades\Cache::remember('sidebar_shortage_count', 30, function() use ($lowStockThreshold) {
        return \App\Models\Product::where('stock_quantity', '<=', $lowStockThreshold)->count();
    });
@endphp

<aside class="w-60 bg-white text-slate-700 flex flex-col shrink-0 border-r border-slate-200/80 select-none h-full shadow-sm">
    
    <!-- Top Brand Header -->
    <div class="h-16 px-4 flex items-center justify-between border-b border-slate-100">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
            <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-black text-sm flex items-center justify-center shadow-sm group-hover:bg-blue-700 transition">
                C
            </div>
            <div class="min-w-0">
                <span class="text-sm font-bold text-slate-900 block leading-tight tracking-tight group-hover:text-blue-600 transition">Counter</span>
                <span class="text-[10px] text-slate-400 font-medium block leading-tight truncate">TYK Bike Shop</span>
            </div>
        </a>
        <a href="{{ route('settings.index') }}" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg text-xs transition" title="Store Settings">
            <i class="fa-regular fa-moon text-xs"></i>
        </a>
    </div>

    <!-- Navigation Links Scroll Area -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-5 scrollbar-thin scrollbar-thumb-slate-200">

        <!-- OVERVIEW -->
        <div>
            <span class="px-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Overview</span>
            <nav class="space-y-0.5">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <i class="fa-solid fa-table-cells-large w-4 text-center text-xs {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Dashboard</span>
                </a>
            </nav>
        </div>

        <!-- SALES -->
        @if($currentUser && ($currentUser->isAdmin() || $currentUser->isCashier()))
        <div>
            <span class="px-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Sales</span>
            <nav class="space-y-0.5">
                <a href="{{ route('orders.create') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('orders.create') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <i class="fa-solid fa-cash-register w-4 text-center text-xs {{ request()->routeIs('orders.create') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Point of Sale</span>
                </a>

                <a href="{{ route('orders.index') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('orders.index') || request()->routeIs('orders.show') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <i class="fa-solid fa-receipt w-4 text-center text-xs {{ request()->routeIs('orders.index') || request()->routeIs('orders.show') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Sales History</span>
                </a>
            </nav>
        </div>
        @endif

        <!-- INVENTORY -->
        <div>
            <span class="px-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Inventory</span>
            <nav class="space-y-0.5">
                <a href="{{ route('products.index') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('products.*') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <i class="fa-solid fa-box-archive w-4 text-center text-xs {{ request()->routeIs('products.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Products</span>
                </a>

                @if($currentUser && ($currentUser->isAdmin() || $currentUser->isInventoryManager()))
                <a href="{{ route('categories.index') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('categories.*') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <i class="fa-solid fa-layer-group w-4 text-center text-xs {{ request()->routeIs('categories.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Categories</span>
                </a>

                <a href="{{ route('suppliers.index') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('suppliers.*') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <i class="fa-solid fa-truck w-4 text-center text-xs {{ request()->routeIs('suppliers.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Suppliers</span>
                </a>

                <a href="{{ route('stock.create') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('stock.create') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <i class="fa-solid fa-arrow-right-arrow-left w-4 text-center text-xs {{ request()->routeIs('stock.create') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Stock In / Out</span>
                </a>

                <a href="{{ route('transactions.index') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('transactions.*') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <i class="fa-solid fa-clock-rotate-left w-4 text-center text-xs {{ request()->routeIs('transactions.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Movements Log</span>
                </a>
                @endif

                <a href="{{ route('alerts.index') }}" 
                   class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('alerts.*') ? 'bg-amber-50 text-amber-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-triangle-exclamation w-4 text-center text-xs {{ $shortageCount > 0 ? 'text-amber-500' : 'text-slate-400' }}"></i>
                        <span>Shortage Alerts</span>
                    </div>
                    @if($shortageCount > 0)
                        <span class="text-[10px] font-bold bg-amber-100 text-amber-800 px-1.5 py-0.2 rounded-md">{{ $shortageCount }}</span>
                    @endif
                </a>
            </nav>
        </div>

        <!-- FINANCE & MANAGEMENT -->
        @if($currentUser && $currentUser->isAdmin())
        <div>
            <span class="px-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Finance & Reports</span>
            <nav class="space-y-0.5">
                <a href="{{ route('analytics.index') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('analytics.*') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <i class="fa-solid fa-chart-line w-4 text-center text-xs {{ request()->routeIs('analytics.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Financial Analytics</span>
                </a>

                <a href="{{ route('reports.index') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('reports.*') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <i class="fa-solid fa-file-pdf w-4 text-center text-xs {{ request()->routeIs('reports.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>PDF Reports</span>
                </a>

                <a href="{{ route('activities.index') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('activities.*') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <i class="fa-solid fa-list-check w-4 text-center text-xs {{ request()->routeIs('activities.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Activity Audit</span>
                </a>
            </nav>
        </div>

        <!-- ADMIN -->
        <div>
            <span class="px-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Admin</span>
            <nav class="space-y-0.5">
                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('users.*') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <i class="fa-solid fa-users w-4 text-center text-xs {{ request()->routeIs('users.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Staff Accounts</span>
                </a>

                <a href="{{ route('settings.index') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition {{ request()->routeIs('settings.*') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                    <i class="fa-solid fa-sliders w-4 text-center text-xs {{ request()->routeIs('settings.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Store Settings</span>
                </a>
            </nav>
        </div>
        @endif

    </div>

    <!-- Bottom User Profile Card & Sign Out -->
    @if($currentUser)
    <div class="p-3 border-t border-slate-100 bg-white">
        <div class="flex items-center justify-between gap-2">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 min-w-0 flex-1 p-1 rounded-xl hover:bg-slate-50 transition group">
                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 border border-blue-200/60 font-black text-xs flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate group-hover:text-blue-600 transition">{{ $currentUser->name }}</div>
                    <div class="text-[10px] text-slate-400 capitalize truncate">{{ $currentUser->role_formatted }}</div>
                </div>
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button type="submit" title="Logout" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                </button>
            </form>
        </div>
    </div>
    @endif

</aside>
