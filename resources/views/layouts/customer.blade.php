<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KickVerse') }} - @yield('title', 'KickVerse - Toko Sepatu Online Terbaik')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Header -->
    @include('layouts.navigation')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Mobile Floating Cart Button -->
    <div class="fixed bottom-4 right-4 z-50 md:hidden">
        <a href="{{ route('cart.index') }}" class="bg-gray-900 text-white p-3 rounded-full shadow-lg hover:bg-gray-800 transition-all duration-200 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"></path>
            </svg>
            @php
                $cartCount = 0;
                if (\Illuminate\Support\Facades\Auth::check()) {
                    $cartCount = \App\Models\Cart::where('user_id', \Illuminate\Support\Facades\Auth::id())->sum('quantity');
                } else {
                    $sessionCart = \Illuminate\Support\Facades\Session::get('cart', []);
                    $cartCount = array_sum(array_column($sessionCart, 'quantity'));
                }
            @endphp
            @if($cartCount > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
            @endif
        </a>
    </div>

    <!-- Footer -->
    @include('layouts.footer')

    @stack('scripts')
    <script>
        // Profile dropdown functionality
        const profileButton = document.getElementById('profile-button');
        const profileDropdown = document.getElementById('profile-dropdown');
        
        if (profileButton && profileDropdown) {
            profileButton.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });
        }

        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                const menu = document.getElementById('mobile-menu');
                menu.classList.toggle('hidden');
            });
        }


    </script>
</body>
</html>