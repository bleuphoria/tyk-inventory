@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <!-- Header & Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Register New Product</h2>
            <p class="text-xs text-gray-500 mt-1">Add a new bicycle unit, replacement component, or accessory to inventory.</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-xs font-bold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 px-4 py-2 rounded-xl transition flex items-center gap-1.5 shadow-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Catalog
        </a>
    </div>

    <!-- Product Form Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" class="w-full text-xs font-semibold px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Supplier -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Supplier / Vendor</label>
                    <select name="supplier_id" class="w-full text-xs font-semibold px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                        <option value="">-- None / Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Product Name -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Trek Marlin 7 Gen 3 Hardtail" 
                           class="w-full text-xs font-semibold px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition" required>
                </div>

                <!-- SKU -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">SKU / Item Code <span class="text-red-500">*</span></label>
                    <input type="text" name="sku" value="{{ old('sku') }}" placeholder="e.g. TRK-M7-2026" 
                           class="w-full text-xs font-mono font-bold px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition" required>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Retail Price -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Retail Price ({{ currency_symbol() }}) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" placeholder="0.00" 
                           class="w-full text-xs font-bold px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition" required>
                </div>

                <!-- Unit Cost -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Cost Price ({{ currency_symbol() }}) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="cost" value="{{ old('cost') }}" placeholder="0.00" 
                           class="w-full text-xs font-bold px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition" required>
                </div>

                <!-- Stock Quantity -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Initial Stock Qty <span class="text-red-500">*</span></label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" 
                           class="w-full text-xs font-bold px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition" required>
                </div>
            </div>

            <!-- Product Image -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Product Image (Cloudinary Cloud Storage)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-200 hover:border-blue-400 rounded-2xl bg-gray-50/50 hover:bg-blue-50/20 transition cursor-pointer relative" id="drop-zone" onclick="document.getElementById('image-input').click()">
                    <div class="space-y-2 text-center" id="upload-placeholder">
                        <div class="w-12 h-12 mx-auto rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <div class="text-xs text-gray-600">
                            <span class="font-bold text-blue-600 hover:text-blue-500">Click to upload photo</span> or drag &amp; drop
                        </div>
                        <p class="text-[11px] text-gray-400">PNG, JPG, WEBP, or GIF up to 5MB (stored on Cloudinary)</p>
                    </div>

                    <!-- Image Preview Container -->
                    <div id="image-preview-container" class="hidden flex flex-col items-center gap-3">
                        <img id="image-preview" src="#" alt="Preview" class="w-32 h-32 object-cover rounded-2xl border-2 border-white shadow-md">
                        <button type="button" onclick="event.stopPropagation(); removeImage();" class="text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-xl transition flex items-center gap-1.5">
                            <i class="fa-solid fa-trash-can text-[11px]"></i> Remove / Change Photo
                        </button>
                    </div>

                    <input id="image-input" name="image" type="file" accept="image/*" class="sr-only" onchange="previewSelectedImage(event)">
                </div>
                @error('image')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('products.index') }}" class="text-xs font-bold text-gray-400 hover:text-gray-700 transition">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-6 py-3 rounded-xl shadow-lg shadow-blue-500/25 transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Save Product to Inventory
                </button>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
    function previewSelectedImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview-container').classList.remove('hidden');
                document.getElementById('upload-placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        const input = document.getElementById('image-input');
        input.value = '';
        document.getElementById('image-preview-container').classList.add('hidden');
        document.getElementById('upload-placeholder').classList.remove('hidden');
    }
</script>
@endpush
@endsection
