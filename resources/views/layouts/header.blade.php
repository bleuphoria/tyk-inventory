@php
    $currentUser = Auth::user();
    $lowStockThreshold = low_stock_threshold();
    $shortageCount = \App\Models\Product::where('stock_quantity', '<=', $lowStockThreshold)->count();
@endphp

<header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 shrink-0 z-10">
    
    <!-- Left: Mobile Menu Toggle & Title / Breadcrumb -->
    <div class="flex items-center gap-3">
        <button type="button" id="mobile-sidebar-toggle" class="lg:hidden w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center text-xs transition">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="flex items-center gap-2 text-xs">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span class="font-bold text-slate-800">{{ shop_name() }}</span>
            <span class="text-slate-300">•</span>
            <span class="text-slate-400 font-medium">Davao City Store</span>
        </div>
    </div>

    <!-- Right: Header Actions -->
    <div class="flex items-center gap-3">
        
        <!-- Shortage Alerts Badge -->
        <a href="{{ route('alerts.index') }}" 
           class="relative flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition {{ $shortageCount > 0 ? 'bg-amber-50 text-amber-900 border border-amber-200 hover:bg-amber-100' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}"
           title="{{ $shortageCount }} inventory shortage alerts">
            <i class="fa-solid fa-triangle-exclamation text-xs {{ $shortageCount > 0 ? 'text-amber-500' : 'text-slate-400' }}"></i>
            <span class="hidden sm:inline">Alerts</span>
            @if($shortageCount > 0)
                <span class="px-1.5 py-0.2 rounded-md bg-amber-200 text-amber-900 text-[10px] font-black">{{ $shortageCount }}</span>
            @endif
        </a>

        <!-- Quick POS Sale CTA -->
        @if($currentUser && ($currentUser->isAdmin() || $currentUser->isCashier()))
        <a href="{{ route('orders.create') }}" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-1.5 rounded-xl text-xs font-bold shadow-xs transition">
            <i class="fa-solid fa-cart-shopping text-xs"></i>
            <span>New Sale</span>
        </a>
        @endif

        <!-- User Role Pill -->
        @if($currentUser)
        <div class="hidden sm:flex items-center gap-2 pl-2 border-l border-slate-200/80">
            @php $badge = $currentUser->role_badge; @endphp
            <span class="text-[11px] px-2 py-0.5 rounded-md font-bold border {{ $badge['class'] }}">
                {{ $currentUser->role_formatted }}
            </span>
        </div>
        @endif

    </div>

</header>
