<nav x-data="{ open: false, scrolled: false }" 
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
     :class="scrolled ? 'bg-white/98 backdrop-blur-sm shadow-lg' : 'bg-white shadow-sm'"
     class="fixed top-0 left-0 right-0 z-50 border-b border-gray-200 text-gray-900 transition-all duration-300">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-8 items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <x-application-logo class="block h-10 w-auto fill-current text-gray-900" />
                </a>
            </div>
            <!-- Centered Navigation Links -->
            <div class="hidden md:flex space-x-8 text-sm font-medium" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; text-transform: uppercase; font-size: 11px;">
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="px-3 py-2" style="text-transform: uppercase; font-size: 11px;">HOME</x-nav-link>
                <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="px-3 py-2" style="text-transform: uppercase; font-size: 11px;">PRODUCTS</x-nav-link>
                
                <!-- Categories Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <div class="px-3 py-2 text-gray-500 hover:text-gray-700 transition-colors duration-200 cursor-pointer" style="text-transform: uppercase; font-size: 11px;">
                        CATEGORIES
                    </div>
                    <div x-show="open" x-transition class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-lg border py-2 z-50">
                        @php
                            $categories = \App\Models\Category::active()->ordered()->get();
                        @endphp
                        @foreach($categories as $category)
                            <a href="{{ route('products.category', $category->slug) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50" style="font-size: 11px;">{{ $category->name }}</a>
                        @endforeach
                        <div class="border-t border-gray-100 my-1"></div>
                        <a href="{{ route('categories.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 font-medium" style="font-size: 11px;">Semua Kategori</a>
                    </div>
                </div>
                
                <x-nav-link :href="route('about')" :active="request()->routeIs('about')" class="px-3 py-2" style="text-transform: uppercase; font-size: 11px;">ABOUT</x-nav-link>
                <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')" class="px-3 py-2" style="text-transform: uppercase; font-size: 11px;">CONTACT</x-nav-link>
            </div>
            <!-- Right Side -->
            <div class="flex items-center space-x-1">
                <!-- User Dropdown / Auth -->
                <div>
                    @auth
                        <div class="relative">
                            <button id="profile-button" class="flex items-center space-x-6 text-gray-700 hover:text-gray-900 focus:outline-none">
                                @php
                                    $user = \Illuminate\Support\Facades\Auth::user();
                                    $avatarUrl = $user->avatar;
                                    
                                    if ($avatarUrl && (str_starts_with($avatarUrl, 'http') || str_starts_with($avatarUrl, 'https'))) {
                                        // Google avatar URL
                                        $finalAvatarUrl = $avatarUrl;
                                    } elseif ($avatarUrl) {
                                        // Local avatar
                                        $finalAvatarUrl = asset('storage/' . $avatarUrl);
                                    } else {
                                        // Default avatar
                                        $finalAvatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff';
                                    }
                                @endphp
                                <!-- Debug: {{ $finalAvatarUrl }} -->
                                <img src="{{ $finalAvatarUrl }}" alt="{{ $user->name }}" class="w-6 h-6 rounded-full object-cover" onerror="console.log('Avatar failed to load:', this.src); this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff';">
                            </button>
                            <div id="profile-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border py-2 z-50 hidden">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ \Illuminate\Support\Facades\Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ \Illuminate\Support\Facades\Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50" style="font-size: 11px;">Profile</a>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50" style="font-size: 11px;">My Orders</a>
                                <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50" style="font-size: 11px;">Wishlist</a>
                                <a href="{{ route('cart.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50" style="font-size: 11px;">Cart</a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50" style="font-size: 11px;">Log Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="liquid-glass-btn login-btn">Login</a>
                        <a href="{{ route('register') }}" class="liquid-glass-btn register-btn">Register</a>
                    @endauth
                </div>
                <!-- Hamburger -->
                <button @click="open = ! open" class="ml-1 md:hidden p-2 rounded hover:bg-gray-100 focus:outline-none">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1 px-4 text-sm" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; text-transform: uppercase;">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" style="text-transform: uppercase;">HOME</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" style="text-transform: uppercase;">PRODUCTS</x-responsive-nav-link>
            
            <!-- Categories Section -->
            <div class="py-2">
                <div class="text-gray-900 font-medium mb-2" style="text-transform: uppercase;">CATEGORIES</div>
                @php
                    $categories = \App\Models\Category::active()->ordered()->take(5)->get();
                @endphp
                @foreach($categories as $category)
                    <x-responsive-nav-link :href="route('products.category', $category->slug)" class="pl-4">{{ $category->name }}</x-responsive-nav-link>
                @endforeach
                <x-responsive-nav-link :href="route('categories.index')" class="pl-4 text-gray-600">Semua Kategori</x-responsive-nav-link>
            </div>
            
            <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')" style="text-transform: uppercase;">ABOUT</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')" style="text-transform: uppercase;">CONTACT</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')" style="text-transform: uppercase;">
                CART <span class="bg-gray-900 text-white rounded-full px-2 ml-2 text-xs">{{ Cart::count() ?? 0 }}</span>
            </x-responsive-nav-link>
        </div>
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 px-4">
                <div class="font-medium text-base text-gray-900">{{ \Illuminate\Support\Facades\Auth::user()->name }}</div>
                <div class="font-medium text-xs text-gray-500">{{ \Illuminate\Support\Facades\Auth::user()->email }}</div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" style="text-transform: uppercase;">PROFILE</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('orders.index')" style="text-transform: uppercase;">MY ORDERS</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('wishlist.index')" style="text-transform: uppercase;">WISHLIST</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" style="text-transform: uppercase;">LOG OUT</x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200 px-4">
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')" style="text-transform: uppercase;">LOGIN</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')" style="text-transform: uppercase;">REGISTER</x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>
