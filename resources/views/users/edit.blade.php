@extends('layouts.app')

@section('title', 'Edit Staff: ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Back & Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('users.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center transition shadow-sm">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit Staff Member</h1>
                <p class="text-xs text-slate-500 mt-0.5">Modify profile, role permissions, or login status for {{ $user->name }}</p>
            </div>
        </div>
        <a href="{{ route('users.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            Cancel
        </a>
    </div>

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl text-xs font-semibold space-y-1">
        <div class="font-bold flex items-center gap-1.5 text-sm">
            <i class="fa-solid fa-circle-exclamation"></i> Please resolve the following errors:
        </div>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Personal Info Card -->
        <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-id-card text-blue-600"></i> Personal & Contact Info
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="0917-xxx-xxxx"
                           class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Account Status <span class="text-rose-500">*</span></label>
                    <select name="is_active" required class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-white">
                        <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Active (Can log in)</option>
                        <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Suspended / Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Role Selection Card -->
        <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-user-shield text-blue-600"></i> Operational Role (RBAC) <span class="text-rose-500">*</span>
            </h2>

            <div class="space-y-3">
                <!-- Admin -->
                <label class="relative flex items-start gap-3.5 p-4 border-2 rounded-2xl cursor-pointer hover:bg-purple-50/40 transition has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50/30">
                    <input type="radio" name="role" value="admin" {{ old('role', $user->role) == 'admin' ? 'checked' : '' }} class="mt-1 text-purple-600 focus:ring-purple-500">
                    <div>
                        <div class="font-bold text-sm text-slate-900 flex items-center gap-1.5">
                            <i class="fa-solid fa-crown text-purple-600"></i> Administrator / Shop Owner
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Full unrestricted control: profit margins, financial analytics, staff RBAC, system settings.</p>
                    </div>
                </label>

                <!-- Inventory Manager -->
                <label class="relative flex items-start gap-3.5 p-4 border-2 rounded-2xl cursor-pointer hover:bg-blue-50/40 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/30">
                    <input type="radio" name="role" value="inventory_manager" {{ old('role', $user->role) == 'inventory_manager' ? 'checked' : '' }} class="mt-1 text-blue-600 focus:ring-blue-500">
                    <div>
                        <div class="font-bold text-sm text-slate-900 flex items-center gap-1.5">
                            <i class="fa-solid fa-boxes-stacked text-blue-600"></i> Inventory Manager
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Products catalog, suppliers, stock in/out adjustments, replenishment alerts, movements ledger.</p>
                    </div>
                </label>

                <!-- Cashier -->
                <label class="relative flex items-start gap-3.5 p-4 border-2 rounded-2xl cursor-pointer hover:bg-emerald-50/40 transition has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/30">
                    <input type="radio" name="role" value="cashier" {{ old('role', $user->role) == 'cashier' ? 'checked' : '' }} class="mt-1 text-emerald-600 focus:ring-emerald-500">
                    <div>
                        <div class="font-bold text-sm text-slate-900 flex items-center gap-1.5">
                            <i class="fa-solid fa-cash-register text-emerald-600"></i> POS Cashier
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">POS checkout, sales receipts, customer order history, product lookup (cost margins hidden).</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Password Card -->
        <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-key text-blue-600"></i> Update Password <span class="text-xs font-normal text-slate-400 lowercase">(leave empty to retain current)</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">New Password</label>
                    <input type="password" name="password" minlength="6" placeholder="Leave blank if unchanged"
                           class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" minlength="6" placeholder="Repeat new password"
                           class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>
            </div>
        </div>

        <div class="pt-2 flex justify-end gap-3">
            <a href="{{ route('users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-blue-600/25 transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Save Staff Profile
            </button>
        </div>
    </form>

</div>
@endsection
