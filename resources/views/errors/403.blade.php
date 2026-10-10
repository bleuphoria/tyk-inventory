<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Restricted Access - TYK Inventory</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#fafbfe] min-h-screen flex items-center justify-center p-6 text-gray-800">

    <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-gray-100 shadow-sm text-center">
        <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-2xl mx-auto mb-5 shadow-sm">
            <i class="fa-solid fa-shield-halved"></i>
        </div>

        <h1 class="text-2xl font-black text-gray-900 mb-2">Access Restricted</h1>
        
        <p class="text-xs text-gray-500 mb-6 leading-relaxed">
            {{ $exception->getMessage() ?: 'You do not have the required role or permissions to access this feature.' }}
        </p>

        @auth
            <div class="bg-gray-50 rounded-2xl p-4 mb-6 border border-gray-100 text-left text-xs">
                <div class="text-gray-400 uppercase font-semibold text-[10px] mb-1">Your Account Info</div>
                <div class="font-bold text-gray-800">{{ auth()->user()->name }}</div>
                <div class="text-gray-500 mt-0.5">{{ auth()->user()->email }}</div>
                <div class="mt-2 pt-2 border-t border-gray-200/60 flex items-center justify-between">
                    <span class="text-gray-400">Assigned Role:</span>
                    <span class="font-bold uppercase px-2 py-0.5 rounded text-[10px] bg-blue-100 text-blue-800">
                        {{ auth()->user()->role_formatted }}
                    </span>
                </div>
            </div>
        @endauth

        <div class="flex flex-col gap-2">
            <a href="{{ route('dashboard') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-sm shadow-blue-500/30">
                <i class="fa-solid fa-arrow-left mr-1"></i> Return to Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-2 px-4 rounded-xl text-xs transition">
                    Sign in with another account
                </button>
            </form>
        </div>
    </div>

</body>
</html>
