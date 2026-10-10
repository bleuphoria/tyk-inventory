@extends('layouts.app')

@section('title', 'Point of Sale (POS Checkout)')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <i class="fa-solid fa-cash-register text-blue-600"></i>
                <span>Point of Sale Checkout</span>
            </h1>
            <p class="text-sm text-slate-500 mt-1">Select bikes and parts, calculate live totals & change due, and print customer invoice</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition">
                <i class="fa-solid fa-receipt text-slate-400"></i>
                <span>Sales History</span>
            </a>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl flex items-center gap-3 text-sm font-semibold">
        <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left 7 Cols: Items Selector & Cart Table -->
            <div class="lg:col-span-7 space-y-4">
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-cart-shopping text-blue-600"></i>
                                <span>Cart Items</span>
                            </h2>
                            <p class="text-xs text-slate-400">Add bikes, parts, or gear to this sale order</p>
                        </div>
                        <button type="button" id="add-row-btn" class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 px-3.5 py-1.5 rounded-xl text-xs font-bold transition">
                            <i class="fa-solid fa-plus text-[10px]"></i> Add Item
                        </button>
                    </div>

                    <!-- Items Container -->
                    <div id="items-container" class="space-y-3">
                        <!-- JS Rows Injected Here -->
                    </div>
                </div>
            </div>

            <!-- Right 5 Cols: Customer, Payment & Live Change Calculator -->
            <div class="lg:col-span-5 space-y-5">
                
                <!-- Customer Details Card -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                    <h2 class="text-sm font-black uppercase tracking-wider text-slate-800 flex items-center gap-2 pb-2 border-b border-slate-100">
                        <i class="fa-regular fa-user text-blue-600"></i> Customer Information
                    </h2>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">Customer Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', 'Walk-in Customer') }}" required
                               class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        @error('customer_name')<span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5">Contact Phone</label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="0917-xxx-xxxx"
                                   class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5">Payment Method <span class="text-rose-500">*</span></label>
                            <select name="payment_method" id="payment_method_select" class="w-full text-sm font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-white" required>
                                <option value="cash" selected>Cash (₱)</option>
                                <option value="gcash">GCash / Maya</option>
                                <option value="card">Credit / Debit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">Order Remarks (Optional)</label>
                        <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Warranty, serial number notes..."
                               class="w-full text-xs font-medium border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <!-- Grand Total & Live Change Due Calculator Card -->
                <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white p-6 sm:p-7 rounded-3xl shadow-xl shadow-slate-900/20 space-y-5 border border-slate-700">
                    
                    <div>
                        <div class="flex justify-between items-baseline">
                            <span class="text-xs uppercase tracking-widest font-bold text-slate-400">Grand Total</span>
                            <span class="text-xs text-slate-400 font-semibold" id="items-count-display">0 items</span>
                        </div>
                        <div class="text-3xl sm:text-4xl font-black text-white mt-1 tracking-tight" id="grand-total-display">
                            {{ currency_symbol() }} 0.00
                        </div>
                    </div>

                    <!-- Live Cash & Change Section -->
                    <div id="cash-calculator-section" class="pt-4 border-t border-slate-700/80 space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                                Cash Received ({{ currency_symbol() }})
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">{{ currency_symbol() }}</span>
                                <input type="number" step="0.01" min="0" id="cash-tendered-input" placeholder="0.00"
                                       class="w-full bg-slate-950/60 border border-slate-700 rounded-xl py-2.5 pl-8 pr-3 text-white font-mono font-bold text-base focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Change Due Indicator -->
                        <div class="p-3.5 rounded-2xl bg-slate-950/40 border border-slate-800 flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Change Due:</span>
                            <span class="text-lg font-black font-mono text-emerald-400" id="change-due-display">{{ currency_symbol() }} 0.00</span>
                        </div>
                        <div id="cash-warning" class="text-xs text-rose-400 font-bold hidden flex items-center gap-1.5">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span id="cash-warning-text">Insufficient cash tendered.</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <button type="submit" id="submit-order-btn" class="w-full bg-blue-600 hover:bg-blue-500 text-white py-3.5 rounded-2xl font-black text-sm tracking-wide shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Complete Sale & Print Receipt</span>
                    </button>
                </div>

            </div>

        </div>
    </form>

</div>

@push('scripts')
<script>
    const currencySymbol = "{{ currency_symbol() }}";
    const productsData = {!! json_encode($products->map(function($p) {
        return [
            'id' => $p->id,
            'name' => $p->name,
            'sku' => $p->sku,
            'price' => (float)$p->price,
            'stock' => (int)$p->stock_quantity,
            'category' => $p->category->name ?? 'General',
        ];
    })) !!};

    let rowIndex = 0;
    let currentGrandTotal = 0;

    function createItemRow() {
        const container = document.getElementById('items-container');
        const rowId = `item-row-${rowIndex}`;

        let optionsHtml = `<option value="">Select bike, part, or accessory...</option>`;
        productsData.forEach(prod => {
            optionsHtml += `<option value="${prod.id}" data-price="${prod.price}" data-stock="${prod.stock}">
                ${prod.name} (${prod.category}) — ${currencySymbol} ${prod.price.toLocaleString('en-US', {minimumFractionDigits: 2})} [In Stock: ${prod.stock}]
            </option>`;
        });

        const rowHtml = `
            <div class="p-4 rounded-2xl border border-slate-200/90 bg-slate-50/60 flex flex-col sm:flex-row gap-3 items-start sm:items-center relative group transition-all" id="${rowId}">
                <div class="flex-1 w-full">
                    <label class="block text-slate-500 text-[11px] font-bold uppercase tracking-wider mb-1">Product</label>
                    <select name="items[${rowIndex}][product_id]" class="product-select w-full border border-slate-200 rounded-xl p-2.5 text-xs font-semibold bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none" required>
                        ${optionsHtml}
                    </select>
                </div>

                <div class="w-full sm:w-36">
                    <label class="block text-slate-500 text-[11px] font-bold uppercase tracking-wider mb-1">Qty</label>
                    <div class="flex items-center border border-slate-200 rounded-xl bg-white overflow-hidden">
                        <button type="button" class="btn-qty-minus px-2.5 py-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition font-bold text-xs">-</button>
                        <input type="number" name="items[${rowIndex}][quantity]" value="1" min="1" max="9999"
                               class="quantity-input w-full text-center text-xs font-black text-slate-800 focus:outline-none p-1" required>
                        <button type="button" class="btn-qty-plus px-2.5 py-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition font-bold text-xs">+</button>
                    </div>
                </div>

                <div class="w-full sm:w-28 sm:text-right">
                    <div class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1">Subtotal</div>
                    <div class="subtotal-display font-black text-slate-900 text-sm">${currencySymbol} 0.00</div>
                </div>

                <button type="button" onclick="removeRow('${rowId}')" class="text-slate-400 hover:text-rose-600 p-2 sm:mt-4 transition" title="Remove item">
                    <i class="fa-solid fa-trash-can text-sm"></i>
                </button>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', rowHtml);

        const currentRow = document.getElementById(rowId);
        const selectEl = currentRow.querySelector('.product-select');
        const qtyEl = currentRow.querySelector('.quantity-input');
        const btnMinus = currentRow.querySelector('.btn-qty-minus');
        const btnPlus = currentRow.querySelector('.btn-qty-plus');

        selectEl.addEventListener('change', () => updateRowCalculations(currentRow));
        qtyEl.addEventListener('input', () => updateRowCalculations(currentRow));

        btnMinus.addEventListener('click', () => {
            let val = parseInt(qtyEl.value) || 1;
            if (val > 1) {
                qtyEl.value = val - 1;
                updateRowCalculations(currentRow);
            }
        });

        btnPlus.addEventListener('click', () => {
            let val = parseInt(qtyEl.value) || 0;
            const maxStock = parseInt(qtyEl.max) || 9999;
            if (val < maxStock) {
                qtyEl.value = val + 1;
                updateRowCalculations(currentRow);
            }
        });

        rowIndex++;
    }

    function removeRow(rowId) {
        const row = document.getElementById(rowId);
        if (document.querySelectorAll('#items-container > div').length > 1) {
            row.remove();
            calculateGrandTotal();
        } else {
            alert('At least one item is required for the checkout.');
        }
    }

    function updateRowCalculations(row) {
        const selectEl = row.querySelector('.product-select');
        const qtyEl = row.querySelector('.quantity-input');
        const subtotalDisplay = row.querySelector('.subtotal-display');

        const selectedOption = selectEl.options[selectEl.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const price = parseFloat(selectedOption.dataset.price) || 0;
            const maxStock = parseInt(selectedOption.dataset.stock) || 1;
            qtyEl.max = maxStock;

            let qty = parseInt(qtyEl.value) || 1;
            if (qty > maxStock) {
                qty = maxStock;
                qtyEl.value = maxStock;
                alert(`Notice: Available stock for this item is ${maxStock} units.`);
            }

            const subtotal = price * qty;
            subtotalDisplay.innerText = currencySymbol + ' ' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        } else {
            subtotalDisplay.innerText = currencySymbol + ' 0.00';
        }

        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        let totalItems = 0;

        document.querySelectorAll('#items-container > div').forEach(row => {
            const selectEl = row.querySelector('.product-select');
            const qtyEl = row.querySelector('.quantity-input');

            const selectedOption = selectEl.options[selectEl.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const price = parseFloat(selectedOption.dataset.price) || 0;
                const qty = parseInt(qtyEl.value) || 0;
                grandTotal += price * qty;
                totalItems += qty;
            }
        });

        currentGrandTotal = grandTotal;
        document.getElementById('grand-total-display').innerText = currencySymbol + ' ' + grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('items-count-display').innerText = `${totalItems} ${totalItems === 1 ? 'item' : 'items'} selected`;

        updateChangeCalculation();
    }

    function updateChangeCalculation() {
        const tenderedInput = document.getElementById('cash-tendered-input');
        const changeDisplay = document.getElementById('change-due-display');
        const warningEl = document.getElementById('cash-warning');
        const warningText = document.getElementById('cash-warning-text');

        const cashTendered = parseFloat(tenderedInput.value) || 0;

        if (cashTendered <= 0 && currentGrandTotal > 0) {
            changeDisplay.innerText = currencySymbol + ' 0.00';
            changeDisplay.className = 'text-lg font-black font-mono text-slate-400';
            warningEl.classList.add('hidden');
            return;
        }

        const change = cashTendered - currentGrandTotal;

        if (change < 0) {
            changeDisplay.innerText = '- ' + currencySymbol + ' ' + Math.abs(change).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            changeDisplay.className = 'text-lg font-black font-mono text-rose-400';
            warningText.innerText = `Insufficient cash (Lacking ${currencySymbol} ${Math.abs(change).toFixed(2)})`;
            warningEl.classList.remove('hidden');
        } else {
            changeDisplay.innerText = currencySymbol + ' ' + change.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            changeDisplay.className = 'text-lg font-black font-mono text-emerald-400';
            warningEl.classList.add('hidden');
        }
    }

    document.getElementById('add-row-btn').addEventListener('click', createItemRow);
    document.getElementById('cash-tendered-input').addEventListener('input', updateChangeCalculation);

    document.getElementById('payment_method_select').addEventListener('change', function() {
        const cashSection = document.getElementById('cash-calculator-section');
        if (this.value === 'cash') {
            cashSection.classList.remove('hidden');
        } else {
            cashSection.classList.add('hidden');
        }
    });

    // Initialize with first item row
    createItemRow();
</script>
@endpush
@endsection
