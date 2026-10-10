@extends('layouts.app')

@section('title', 'Activity Audit Trail')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Activity Audit Trail</h1>
            <p class="text-sm text-slate-500 mt-1">System-wide event log of product edits, stock operations, and POS checkouts</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('activities.index') }}" method="GET" class="flex gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search action type, user, or details..."
                       class="w-full pl-9 pr-3 py-2 text-xs font-medium border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-xs font-bold transition">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('activities.index') }}" class="bg-slate-100 text-slate-600 px-3 py-2 rounded-xl text-xs hover:bg-slate-200 transition flex items-center font-bold">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Activity Timeline Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-3">
        @forelse($activities as $activity)
        <div class="flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-inner">
                <i class="fa-solid fa-{{ $activity->icon ?? 'bell' }} text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-sm text-slate-900">{{ $activity->action }}</div>
                <div class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $activity->description }}</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-xs font-bold text-slate-700">{{ $activity->created_at->diffForHumans() }}</div>
                <div class="text-[11px] text-slate-400 font-mono">{{ $activity->created_at->format('M d, Y h:i A') }}</div>
            </div>
        </div>
        @empty
        <div class="py-12 text-center text-slate-400">
            <i class="fa-solid fa-list-check text-4xl mb-3 block text-slate-300"></i>
            <span class="font-semibold text-slate-600">No activity logs recorded yet.</span>
        </div>
        @endforelse

        @if($activities->hasPages())
        <div class="pt-4 border-t border-slate-100">
            {{ $activities->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
