@extends('layouts.app')

@section('title', 'Store Settings & Davao City Localization')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Store Settings & Davao City Localization</h1>
            <p class="text-sm text-slate-500 mt-1">Configure store identity, Philippine Peso currency (₱), VAT policies, and invoice disclaimers</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 px-4 py-2 rounded-2xl flex items-center gap-2.5 text-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <div>
                <span class="text-slate-500 font-semibold">Active Currency:</span>
                <strong class="text-blue-700 font-black ml-1">{{ $settings['currency_symbol'] ?? '₱' }} ({{ $settings['currency_code'] ?? 'PHP' }})</strong>
            </div>
        </div>
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

    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Section 1: Store Identity & Davao City Address -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <span class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-inner">
                    <i class="fa-solid fa-store"></i>
                </span>
                <div>
                    <h2 class="text-base font-black text-slate-900">Store Identity & Business Contact</h2>
                    <p class="text-xs text-slate-400">Printed on official invoices, customer receipts, and PDF reports</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Business / Store Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="shop_name" value="{{ old('shop_name', $settings['shop_name'] ?? 'TYK Enterprises') }}" required
                           class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Business Tagline</label>
                    <input type="text" name="shop_tagline" value="{{ old('shop_tagline', $settings['shop_tagline'] ?? 'Specialized Bikes & Premium Components') }}"
                           class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Physical Store Address (Davao City)</label>
                <input type="text" name="shop_address" value="{{ old('shop_address', $settings['shop_address'] ?? 'J.P. Laurel Ave, Bajada, Davao City, 8000 Davao del Sur') }}"
                       class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Store Contact Phone</label>
                    <input type="text" name="shop_phone" value="{{ old('shop_phone', $settings['shop_phone'] ?? '(082) 224-8990 / 0917-555-8952') }}"
                           class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Store Email Address</label>
                    <input type="email" name="shop_email" value="{{ old('shop_email', $settings['shop_email'] ?? 'tykdavao@gmail.com') }}"
                           class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Business TIN (BIR Registered)</label>
                    <input type="text" name="shop_tin" value="{{ old('shop_tin', $settings['shop_tin'] ?? '402-179-883-000') }}" placeholder="123-456-789-000"
                           class="w-full text-sm font-mono border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>
            </div>
        </div>

        <!-- Section 2: Currency & Formatting Rules -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <span class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-inner">
                    <i class="fa-solid fa-peso-sign"></i>
                </span>
                <div>
                    <h2 class="text-base font-black text-slate-900">Currency & Formatting Rules</h2>
                    <p class="text-xs text-slate-400">Sets the monetary format throughout POS checkout, inventory valuation, and analytics</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-2">Select Currency Preset</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center gap-3 p-4 border-2 rounded-2xl cursor-pointer hover:bg-emerald-50/40 transition has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/30">
                            <input type="radio" name="currency_preset" value="PHP" {{ ($settings['currency_code'] ?? 'PHP') == 'PHP' ? 'checked' : '' }} onchange="setCurrencyPreset('₱', 'PHP')" class="text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <div class="font-bold text-sm text-slate-900 flex items-center gap-1.5">
                                    <span>Philippine Peso (₱)</span>
                                    <span class="text-[10px] bg-emerald-100 text-emerald-800 px-1.5 py-0.2 rounded font-black">Davao Local</span>
                                </div>
                                <span class="text-xs text-slate-500">Symbol: ₱ | Code: PHP</span>
                            </div>
                        </label>

                        <label class="relative flex items-center gap-3 p-4 border-2 rounded-2xl cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/30">
                            <input type="radio" name="currency_preset" value="USD" {{ ($settings['currency_code'] ?? '') == 'USD' ? 'checked' : '' }} onchange="setCurrencyPreset('$', 'USD')" class="text-blue-600 focus:ring-blue-500">
                            <div>
                                <div class="font-bold text-sm text-slate-900">US Dollar ($)</div>
                                <span class="text-xs text-slate-500">Symbol: $ | Code: USD</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Symbol Placement</label>
                    <select name="currency_position" class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-white">
                        <option value="before" {{ ($settings['currency_position'] ?? 'before') == 'before' ? 'selected' : '' }}>Before Amount (e.g. ₱1,500.00)</option>
                        <option value="after" {{ ($settings['currency_position'] ?? '') == 'after' ? 'selected' : '' }}>After Amount (e.g. 1,500.00 ₱)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Currency Symbol <span class="text-rose-500">*</span></label>
                    <input type="text" name="currency_symbol" id="currencySymbolInput" value="{{ old('currency_symbol', $settings['currency_symbol'] ?? '₱') }}" required
                           class="w-full text-sm font-black border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">ISO Currency Code <span class="text-rose-500">*</span></label>
                    <input type="text" name="currency_code" id="currencyCodeInput" value="{{ old('currency_code', $settings['currency_code'] ?? 'PHP') }}" required
                           class="w-full text-sm font-mono uppercase font-bold border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>
            </div>
        </div>

        <!-- Section 3: Tax & Inventory Thresholds -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <span class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shadow-inner">
                    <i class="fa-solid fa-receipt"></i>
                </span>
                <div>
                    <h2 class="text-base font-black text-slate-900">Tax & Inventory Thresholds</h2>
                    <p class="text-xs text-slate-400">Configure value-added tax rates and reorder shortage warning triggers</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">VAT / Tax Rate (%) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="number" step="0.01" min="0" max="100" name="tax_rate" value="{{ old('tax_rate', $settings['tax_rate'] ?? '12') }}" required
                               class="w-full text-sm font-bold border border-slate-200 rounded-xl p-3 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <span class="absolute right-3.5 top-3 text-slate-400 text-xs font-bold">%</span>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">12% standard Philippine VAT rate</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Tax Calculation Mode</label>
                    <select name="tax_mode" class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-white">
                        <option value="inclusive" {{ ($settings['tax_mode'] ?? 'inclusive') == 'inclusive' ? 'selected' : '' }}>VAT Inclusive (Built into retail price)</option>
                        <option value="exclusive" {{ ($settings['tax_mode'] ?? '') == 'exclusive' ? 'selected' : '' }}>VAT Exclusive (Added at checkout)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Low Stock Warning Trigger <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="number" min="1" max="1000" name="low_stock_threshold" value="{{ old('low_stock_threshold', $settings['low_stock_threshold'] ?? '15') }}" required
                               class="w-full text-sm font-bold border border-slate-200 rounded-xl p-3 pr-14 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <span class="absolute right-3.5 top-3 text-slate-400 text-xs font-semibold">units</span>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">Triggers shortage alerts badge</span>
                </div>
            </div>
        </div>

        <!-- Section 4: Receipt Policy & Disclaimers -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <span class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-inner">
                    <i class="fa-solid fa-file-invoice"></i>
                </span>
                <div>
                    <h2 class="text-base font-black text-slate-900">Receipt & Warranty Disclaimers</h2>
                    <p class="text-xs text-slate-400">Printed on official invoices and customer POS receipts</p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Receipt Footer Message</label>
                <input type="text" name="receipt_footer" value="{{ old('receipt_footer', $settings['receipt_footer'] ?? "Davao City's Premier Bike Hub. Daghang Salamat sa inyong pagsalig!") }}"
                       class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Warranty & Component Terms</label>
                <textarea name="warranty_policy" rows="3"
                          class="w-full text-sm border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">{{ old('warranty_policy', $settings['warranty_policy'] ?? 'Official manufacturer warranty applies to genuine Shimano, Trek, SRAM, and Maxxis components. Please retain this invoice for warranty verification.') }}</textarea>
            </div>
        </div>

        <div class="pt-2 flex justify-end gap-3">
            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-7 py-3 rounded-xl text-xs font-bold shadow-lg shadow-blue-600/25 transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Save Store Settings
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
    function setCurrencyPreset(symbol, code) {
        document.getElementById('currencySymbolInput').value = symbol;
        document.getElementById('currencyCodeInput').value = code;
    }
</script>
@endpush
@endsection
