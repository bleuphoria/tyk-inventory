@extends('layouts.app')

@section('title', 'Staff & Access Management (RBAC)')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Staff & Access Control (RBAC)</h1>
            <p class="text-sm text-slate-500 mt-1">Manage employee accounts, assign operational roles, and enforce route authorization</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-600/20 transition-all">
                <i class="fa-solid fa-user-plus"></i>
                <span>Add Staff Account</span>
            </a>
        </div>
    </div>

    <!-- Role KPI Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Staff</span>
            <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ $totalUsers }}</span>
            <span class="text-[11px] text-slate-400 block mt-1">All team members</span>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm">
            <span class="text-[11px] font-bold text-purple-600 uppercase tracking-wider block mb-1">Admins</span>
            <span class="text-2xl sm:text-3xl font-black text-purple-700">{{ $adminCount }}</span>
            <span class="text-[11px] text-slate-400 block mt-1">Full access & financial margins</span>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm">
            <span class="text-[11px] font-bold text-blue-600 uppercase tracking-wider block mb-1">Inventory</span>
            <span class="text-2xl sm:text-3xl font-black text-blue-700">{{ $inventoryCount }}</span>
            <span class="text-[11px] text-slate-400 block mt-1">Stock movements & vendors</span>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm">
            <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider block mb-1">Cashiers</span>
            <span class="text-2xl sm:text-3xl font-black text-emerald-700">{{ $cashierCount }}</span>
            <span class="text-[11px] text-slate-400 block mt-1">POS sales & customer receipts</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-7 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search staff by name, email, or phone..."
                       class="w-full pl-9 pr-3 py-2 text-xs font-medium border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div class="sm:col-span-4">
                <select name="role" class="w-full py-2 px-3 text-xs font-medium border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-slate-700 bg-white">
                    <option value="">All Operational Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator / Owner</option>
                    <option value="inventory_manager" {{ request('role') == 'inventory_manager' ? 'selected' : '' }}>Inventory Manager</option>
                    <option value="cashier" {{ request('role') == 'cashier' ? 'selected' : '' }}>POS Cashier</option>
                </select>
            </div>

            <div class="sm:col-span-1 flex gap-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold flex items-center justify-center p-2 transition" title="Filter Staff">
                    <i class="fa-solid fa-filter"></i>
                </button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('users.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold flex items-center justify-center p-2 transition" title="Clear Filters">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Staff Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Staff Member</th>
                        <th class="py-3.5 px-5">Contact</th>
                        <th class="py-3.5 px-5">Assigned Role</th>
                        <th class="py-3.5 px-5 text-center">Status</th>
                        <th class="py-3.5 px-5">Registered</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0284c7&color=fff&bold=true" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-sm flex items-center gap-1.5">
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                            <span class="text-[10px] bg-blue-100 text-blue-700 px-1.5 py-0.2 rounded font-bold">(You)</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-slate-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-5 text-slate-600 font-mono">
                            {{ $user->phone ?: '—' }}
                        </td>
                        <td class="py-4 px-5 whitespace-nowrap">
                            @php $badge = $user->role_badge; @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $badge['class'] }}">
                                @if($user->role === 'admin')
                                    <i class="fa-solid fa-crown text-[10px]"></i>
                                @elseif($user->role === 'inventory_manager')
                                    <i class="fa-solid fa-boxes-stacked text-[10px]"></i>
                                @elseif($user->role === 'cashier')
                                    <i class="fa-solid fa-cash-register text-[10px]"></i>
                                @endif
                                {{ $user->role_formatted }}
                            </span>
                        </td>
                        <td class="py-4 px-5 text-center whitespace-nowrap">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Suspended
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-slate-400 whitespace-nowrap">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-4 px-5 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('users.edit', $user) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Staff Profile">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete staff account {{ $user->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Account">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-slate-400">
                            <i class="fa-solid fa-users-slash text-4xl mb-3 block text-slate-300"></i>
                            <span class="font-semibold text-slate-600">No staff members found matching the filter.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
