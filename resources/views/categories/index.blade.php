@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Categories</h1>
            <p class="text-sm text-slate-500 mt-1">Manage product classifications, bikes, parts, and accessories</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-600/20 transition-all">
                <i class="fa-solid fa-plus"></i>
                <span>Add Category</span>
            </a>
        </div>
    </div>

    <!-- Category Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($categories as $category)
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
            <div>
                <div class="flex justify-between items-start mb-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                        <i class="fa-solid fa-folder-tree"></i>
                    </div>
                    <span class="bg-slate-100 text-slate-700 text-xs px-2.5 py-1 rounded-full font-bold">
                        {{ $category->products_count }} {{ Str::plural('Product', $category->products_count) }}
                    </span>
                </div>
                <h3 class="text-base font-bold text-slate-800">{{ $category->name }}</h3>
                <p class="text-slate-500 text-xs mt-2 line-clamp-2 leading-relaxed">
                    {{ $category->description ?: 'No description provided for this classification.' }}
                </p>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] font-mono text-slate-400">ID: #{{ $category->id }}</span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('categories.edit', $category) }}" class="inline-flex items-center gap-1 text-slate-600 hover:text-blue-600 bg-slate-100 hover:bg-blue-50 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete category {{ $category->name }}?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition" title="Delete Category">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl mb-4">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">No categories found</h3>
            <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">Categorizing your bikes, parts, and apparel keeps your inventory structured.</p>
            <div class="mt-6">
                <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-600/20 transition">
                    <i class="fa-solid fa-plus"></i> Create First Category
                </a>
            </div>
        </div>
        @endforelse
    </div>

</div>
@endsection
