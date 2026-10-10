@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Account Profile & Security</h1>
            <p class="text-sm text-slate-500 mt-1">Update personal account details, change login password, or manage session settings</p>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Update Profile Info Card -->
        <div class="p-6 sm:p-8 bg-white border border-slate-200/80 shadow-sm rounded-3xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password Card -->
        <div class="p-6 sm:p-8 bg-white border border-slate-200/80 shadow-sm rounded-3xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete Account Card -->
        <div class="p-6 sm:p-8 bg-white border border-slate-200/80 shadow-sm rounded-3xl">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>

</div>
@endsection
