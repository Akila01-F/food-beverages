<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - Food & Beverage Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-food-cream">
    <!-- Navigation -->
    <nav class="bg-food-dark border-b border-food-secondary shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                        <span class="text-2xl">👑</span>
                        <div>
                            <h1 class="text-xl font-bold text-white">Admin Panel</h1>
                            <p class="text-xs text-food-accent">Food & Beverage</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-food-accent px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.dashboard*') ? 'bg-food-secondary' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="text-white hover:text-food-accent px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.products*') ? 'bg-food-secondary' : '' }}">
                        Products
                    </a>
                    <a href="{{ route('admin.orders') }}" class="text-white hover:text-food-accent px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.orders*') ? 'bg-food-secondary' : '' }}">
                        Orders
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="text-white hover:text-food-accent px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.categories*') ? 'bg-food-secondary' : '' }}">
                        Categories
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="text-white hover:text-food-accent px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.users*') ? 'bg-food-secondary' : '' }}">
                        Users
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Main Site Link -->
                    <a href="{{ route('home') }}" class="text-food-accent hover:text-white text-sm">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        View Site
                    </a>
                    
                    <!-- Admin User Info -->
                    <div class="flex items-center space-x-3">
                        <div class="text-right">
                            <p class="text-white text-sm font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-food-accent text-xs">Administrator</p>
                        </div>
                        <div class="h-8 w-8 bg-food-secondary rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-food-accent hover:text-white text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-food-text-dark">
                <a href="{{ route('admin.dashboard') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.dashboard*') ? 'bg-food-secondary' : '' }}">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.products*') ? 'bg-food-secondary' : '' }}">Products</a>
                <a href="{{ route('admin.orders') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.orders*') ? 'bg-food-secondary' : '' }}">Orders</a>
                <a href="{{ route('admin.categories.index') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.categories*') ? 'bg-food-secondary' : '' }}">Categories</a>
                <a href="{{ route('admin.users.index') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.users*') ? 'bg-food-secondary' : '' }}">Users</a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg">
                {{ session('warning') }}
            </div>
        @endif

        <!-- Page Header -->
        <div class="mb-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-food-dark sm:text-3xl sm:truncate">
                        @yield('page-title', 'Dashboard')
                    </h2>
                    @hasSection('page-description')
                        <p class="mt-1 text-sm text-food-text-muted">
                            @yield('page-description')
                        </p>
                    @endif
                </div>
                @hasSection('page-actions')
                    <div class="mt-4 flex md:mt-0 md:ml-4">
                        @yield('page-actions')
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        @yield('content')
    </main>

    @stack('scripts')
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>