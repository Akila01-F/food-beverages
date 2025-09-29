<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-food-light">
    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <span class="text-3xl">🍔</span>
                        <span class="text-2xl font-bold text-food-primary">Cafe Sén
                        </span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-food-dark hover:text-food-primary transition {{ request()->routeIs('home') ? 'text-food-primary font-semibold' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('products.index') }}" class="text-food-dark hover:text-food-primary transition {{ request()->routeIs('products.*') ? 'text-food-primary font-semibold' : '' }}">
                        Menu
                    </a>
                    <a href="{{ route('about') }}" class="text-food-dark hover:text-food-primary transition {{ request()->routeIs('about') ? 'text-food-primary font-semibold' : '' }}">
                        About
                    </a>
                    <a href="{{ route('contact') }}" class="text-food-dark hover:text-food-primary transition {{ request()->routeIs('contact') ? 'text-food-primary font-semibold' : '' }}">
                        Contact
                    </a>
                </nav>

                <!-- Cart & Auth -->
                <div class="flex items-center space-x-4">
                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-food-dark hover:text-food-primary transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.68 9.93m1.68-9.93h10m-10 0L5.4 5M7 13l-1.68 9.93h11.36L15 13"></path>
                        </svg>
                        @php
                            $cartCount = session('cart', []) ? array_sum(array_column(session('cart', []), 'quantity')) : 0;
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-food-primary text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Authentication -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-food-dark hover:text-food-primary transition">
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full">
                                <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-food-dark hover:bg-food-cream">Dashboard</a>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-food-dark hover:bg-food-cream">My Orders</a>
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-food-dark hover:bg-food-cream">Profile</a>
                                                            <div class="mt-2">
                                <form method="POST" action="{{ route('auth.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3 py-2 text-sm text-food-dark hover:bg-food-light transition">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('auth.login') }}" class="text-food-dark hover:text-food-primary transition">
                            Login
                        </a>
                        <a href="{{ route('auth.register') }}" class="bg-food-primary hover:bg-food-warning text-white px-4 py-2 rounded-lg transition">
                            Sign Up
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden p-2" x-data x-on:click="$dispatch('toggle-mobile-menu')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-data="{ open: false }" x-on:toggle-mobile-menu.window="open = !open" x-show="open" x-transition class="md:hidden py-4 border-t">
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('home') }}" class="py-2 text-food-dark hover:text-food-primary">Home</a>
                    <a href="{{ route('products.index') }}" class="py-2 text-food-dark hover:text-food-primary">Menu</a>
                    <a href="{{ route('about') }}" class="py-2 text-food-dark hover:text-food-primary">About</a>
                    <a href="{{ route('contact') }}" class="py-2 text-food-dark hover:text-food-primary">Contact</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-food-dark text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="text-3xl">🍔</span>
                        <span class="text-2xl font-bold text-food-secondary">Food & Beverage</span>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Delicious food delivered fresh to your doorstep. From mouth-watering burgers to refreshing beverages, 
                        we bring you the best flavors from around the world.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold text-food-secondary mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition">Menu</a></li>
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold text-food-secondary mb-4">Contact Info</h3>
                    <div class="space-y-2 text-gray-300">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-food-secondary" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                            </svg>
                            <span>+1 (555) 123-4567</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-food-secondary" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
                            <span>hello@foodbeverage.com</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-600 mt-8 pt-8 text-center">
                <div class="flex flex-col md:flex-row justify-between items-center text-gray-300">
                    <p>&copy; {{ date('Y') }} Food & Beverage. All rights reserved.</p>
                    <p class="mt-2 md:mt-0 text-sm">
                        Built with ❤️ using <span class="text-food-secondary">Laravel • Jetstream • Livewire • Tailwind CSS</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>