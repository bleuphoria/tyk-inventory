<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ shop_name() }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- App Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-gray-800 bg-[#f8fafc] flex overflow-hidden">

    <!-- Desktop Sidebar -->
    <div class="hidden lg:flex shrink-0">
        @include('layouts.sidebar')
    </div>

    <!-- Mobile Slide-over Drawer Backdrop & Sidebar -->
    <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-slate-950/60 z-50 backdrop-blur-sm hidden transition-opacity lg:hidden">
        <div id="mobile-sidebar-panel" class="fixed inset-y-0 left-0 w-64 max-w-xs flex z-50 transform -translate-x-full transition-transform duration-300 ease-in-out">
            @include('layouts.sidebar')
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        
        <!-- Top Navigation Bar -->
        @include('layouts.header')

        <!-- Scrollable Page Canvas -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-8 scrollbar-thin scrollbar-thumb-gray-200">
            @yield('content')
        </main>

    </div>

    <!-- Floating Global Toast Notifications Container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 pointer-events-none">
        
        @if(session('success'))
        <div class="toast-item pointer-events-auto flex items-center gap-3 bg-emerald-600 text-white px-5 py-3.5 rounded-2xl shadow-xl shadow-emerald-600/30 text-sm font-semibold transform transition-all duration-300 translate-y-0 opacity-100">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <span>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="ml-2 text-white/70 hover:text-white"><i class="fa-solid fa-xmark text-xs"></i></button>
        </div>
        @endif

        @if(session('error'))
        <div class="toast-item pointer-events-auto flex items-center gap-3 bg-rose-600 text-white px-5 py-3.5 rounded-2xl shadow-xl shadow-rose-600/30 text-sm font-semibold transform transition-all duration-300 translate-y-0 opacity-100">
            <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            <span>{{ session('error') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="ml-2 text-white/70 hover:text-white"><i class="fa-solid fa-xmark text-xs"></i></button>
        </div>
        @endif

        @if(session('warning'))
        <div class="toast-item pointer-events-auto flex items-center gap-3 bg-amber-600 text-white px-5 py-3.5 rounded-2xl shadow-xl shadow-amber-600/30 text-sm font-semibold transform transition-all duration-300 translate-y-0 opacity-100">
            <i class="fa-solid fa-circle-exclamation text-lg"></i>
            <span>{{ session('warning') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="ml-2 text-white/70 hover:text-white"><i class="fa-solid fa-xmark text-xs"></i></button>
        </div>
        @endif

    </div>

    <!-- Mobile Drawer & Auto-dismiss Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('mobile-sidebar-toggle');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            const panel = document.getElementById('mobile-sidebar-panel');

            if (toggleBtn && backdrop && panel) {
                const openDrawer = () => {
                    backdrop.classList.remove('hidden');
                    setTimeout(() => panel.classList.remove('-translate-x-full'), 10);
                };

                const closeDrawer = () => {
                    panel.classList.add('-translate-x-full');
                    setTimeout(() => backdrop.classList.add('hidden'), 300);
                };

                toggleBtn.addEventListener('click', openDrawer);
                backdrop.addEventListener('click', (e) => {
                    if (e.target === backdrop) closeDrawer();
                });
            }

            // Auto-dismiss toasts after 4 seconds
            const toasts = document.querySelectorAll('.toast-item');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-2');
                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
