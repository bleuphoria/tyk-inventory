@extends('layouts.app')

@section('title', 'Record Stock Movement')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Back & Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('transactions.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center transition shadow-sm">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Stock Movement Entry</h1>
                <p class="text-xs text-slate-500 mt-0.5">Record received supplier shipment (In) or inventory adjustment / damage (Out)</p>
            </div>
        </div>
        <a href="{{ route('transactions.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            Cancel
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('stock.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Product Selection -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Select Product <span class="text-rose-500">*</span></label>
                <select name="product_id" class="w-full text-sm font-medium border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition bg-white" required autofocus>
                    <option value="">Choose bike, component, or accessory...</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ (request('product_id') == $product->id || old('product_id') == $product->id) ? 'selected' : '' }}>
                            {{ $product->name }} (SKU: {{ $product->sku }}) — Current Stock: {{ $product->stock_quantity }} units
                        </option>
                    @endforeach
                </select>
                @error('product_id')<span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>@enderror
            </div>

            <!-- Movement Type Selection -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Movement Type <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center gap-3 p-4 border-2 rounded-2xl cursor-pointer hover:bg-emerald-50/40 transition has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/30">
                        <input type="radio" name="type" value="in" {{ (request('type', 'in') === 'in' || old('type', 'in') === 'in') ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <div class="font-bold text-sm text-emerald-800 flex items-center gap-1.5">
                                <i class="fa-solid fa-arrow-down"></i> Stock In (+)
                            </div>
                            <span class="text-[11px] text-slate-500">Supplier delivery / Restock</span>
                        </div>
                    </label>

                    <label class="relative flex items-center gap-3 p-4 border-2 rounded-2xl cursor-pointer hover:bg-purple-50/40 transition has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50/30">
                        <input type="radio" name="type" value="out" {{ (request('type') === 'out' || old('type') === 'out') ? 'checked' : '' }} class="text-purple-600 focus:ring-purple-500">
                        <div>
                            <div class="font-bold text-sm text-purple-800 flex items-center gap-1.5">
                                <i class="fa-solid fa-arrow-up"></i> Stock Out (-)
                            </div>
                            <span class="text-[11px] text-slate-500">Manual deduction / Audit / Damage</span>
                        </div>
                    </label>
                </div>
                @error('type')<span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Quantity <span class="text-rose-500">*</span></label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}"
                           class="w-full text-sm font-bold border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition" required>
                    @error('quantity')<span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">PO / Reference Code</label>
                    <input type="text" name="reference" value="{{ old('reference') }}" placeholder="e.g. PO-2026-088 / ADJ-01"
                           class="w-full text-sm font-mono border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
                    @error('reference')<span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>@enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Audit Notes / Reason (Optional)</label>
                <textarea name="notes" rows="3" placeholder="Additional details, supplier invoice number, batch numbers..."
                          class="w-full text-sm border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">{{ old('notes') }}</textarea>
                @error('notes')<span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>@enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('transactions.index') }}" class="text-slate-500 hover:text-slate-800 text-sm font-semibold transition">
                    Discard
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-blue-600/25 transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Record Movement</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
