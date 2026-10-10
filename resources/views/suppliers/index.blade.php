@extends('layouts.app')

@section('title', 'Suppliers & Vendors')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Suppliers & Distributors</h1>
            <p class="text-sm text-slate-500 mt-1">Manage bike brands, wholesale parts manufacturers, and contact directories</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('suppliers.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-600/20 transition-all">
                <i class="fa-solid fa-plus"></i>
                <span>Add Supplier</span>
            </a>
        </div>
    </div>

    <!-- Suppliers Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($suppliers as $supplier)
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl shadow-inner">
                            <i class="fa-solid fa-truck-ramp-box"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800 leading-tight">{{ $supplier->name }}</h3>
                            @if($supplier->contact_name)
                                <span class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5">
                                    <i class="fa-regular fa-user text-slate-400"></i> {{ $supplier->contact_name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-2 text-xs text-slate-600 pt-3 border-t border-slate-100">
                    @if($supplier->email)
                        <div class="flex items-center gap-2.5">
                            <i class="fa-regular fa-envelope text-slate-400 w-4 text-center"></i>
                            <a href="mailto:{{ $supplier->email }}" class="text-blue-600 hover:underline truncate">{{ $supplier->email }}</a>
                        </div>
                    @endif
                    @if($supplier->phone)
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-phone text-slate-400 w-4 text-center"></i>
                            <span class="font-mono text-slate-700">{{ $supplier->phone }}</span>
                        </div>
                    @endif
                    @if($supplier->address)
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-location-dot text-slate-400 w-4 text-center mt-0.5"></i>
                            <span class="text-slate-500 line-clamp-2">{{ $supplier->address }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-full font-bold">
                    {{ $supplier->products_count }} {{ Str::plural('Product', $supplier->products_count) }}
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="inline-flex items-center gap-1 text-slate-600 hover:text-blue-600 bg-slate-100 hover:bg-blue-50 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Delete this supplier record?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition" title="Delete Supplier">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl mb-4">
                <i class="fa-solid fa-truck-field"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">No suppliers registered</h3>
            <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">Register your component vendors (Shimano, Trek, SRAM, Maxxis) to streamline reorders.</p>
            <div class="mt-6">
                <a href="{{ route('suppliers.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-600/20 transition">
                    <i class="fa-solid fa-plus"></i> Add First Supplier
                </a>
            </div>
        </div>
        @endforelse
    </div>

</div>
@endsection
