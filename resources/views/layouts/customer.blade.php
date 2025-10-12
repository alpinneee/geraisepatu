<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Toko Sepatu') }} - @yield('title', 'Toko Sepatu Online Terbaik')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('resources/css/liquid-glass.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/liquid-glass.css', 'resources/js/app.js'])
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

        // Wishlist functionality
        function toggleWishlist(productId) {
            fetch('/wishlist/check', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.in_wishlist) {
                    // Remove from wishlist
                    removeFromWishlist(productId);
                } else {
                    // Add to wishlist
                    addToWishlist(productId);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function addToWishlist(productId) {
            fetch('/wishlist/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    const button = document.querySelector(`[data-product-id="${productId}"]`);
                    if (button) {
                        const svg = button.querySelector('svg');
                        svg.setAttribute('fill', 'currentColor');
                        button.classList.add('text-red-500');
                    }
                    // Show success message
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error adding to wishlist', 'error');
            });
        }

        function removeFromWishlist(productId) {
            fetch('/wishlist/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    const button = document.querySelector(`[data-product-id="${productId}"]`);
                    if (button) {
                        const svg = button.querySelector('svg');
                        svg.setAttribute('fill', 'none');
                        button.classList.remove('text-red-500');
                    }
                    // Show success message
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error removing from wishlist', 'error');
            });
        }

        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-md shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;

            // Add to page
            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</body>
</html>