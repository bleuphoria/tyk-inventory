@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Back & Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('categories.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center transition shadow-sm">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit Category</h1>
                <p class="text-xs text-slate-500 mt-0.5">Modify {{ $category->name }} details</p>
            </div>
        </div>
        <a href="{{ route('categories.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            Cancel
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Category Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}"
                       class="w-full text-sm font-medium border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition" required>
                @error('name')<span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Description (Optional)</label>
                <textarea name="description" rows="4"
                          class="w-full text-sm border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">{{ old('description', $category->description) }}</textarea>
                @error('description')<span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>@enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('categories.index') }}" class="text-slate-500 hover:text-slate-800 text-sm font-semibold transition">
                    Discard
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-blue-600/25 transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Update Category</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
