@extends('layouts.app')

@section('title', 'Add Supplier')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Back & Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('suppliers.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center transition shadow-sm">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Register Supplier</h1>
                <p class="text-xs text-slate-500 mt-0.5">Add a new vendor, distributor, or brand manufacturer</p>
            </div>
        </div>
        <a href="{{ route('suppliers.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            Cancel
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Company / Supplier Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Shimano Philippines / Neo Zigma Motors"
                       class="w-full text-sm font-medium border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition" required autofocus>
                @error('name')<span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Contact Person</label>
                <input type="text" name="contact_name" value="{{ old('contact_name') }}" placeholder="e.g. Kenji Takahashi (Sales Manager)"
                       class="w-full text-sm font-medium border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
                @error('contact_name')<span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="orders@shimano-ph.com"
                           class="w-full text-sm font-medium border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
                    @error('email')<span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+63 917 123 4567"
                           class="w-full text-sm font-medium border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
                    @error('phone')<span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>@enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Office / Warehouse Address</label>
                <textarea name="address" rows="3" placeholder="Building name, street, city, province..."
                          class="w-full text-sm border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">{{ old('address') }}</textarea>
                @error('address')<span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>@enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('suppliers.index') }}" class="text-slate-500 hover:text-slate-800 text-sm font-semibold transition">
                    Discard
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-blue-600/25 transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Save Supplier</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
