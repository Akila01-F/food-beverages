<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Food & Beverage') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-orange-500 to-red-500 text-black shadow-xl">
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-utensils text-2xl mr-3 text-orange-100"></i>
                    <h2 class="text-xl font-bold">Admin Panel</h2>
                </div>
            </div>
            
            <nav class="mt-6">
                <div class="px-6 py-2">
                    <p class="text-orange-100 text-xs uppercase tracking-wider font-semibold">Main</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-6 py-3 text-black hover:bg-orange-400/30 transition-colors {{ request()->routeIs('admin.dashboard*') ? 'bg-orange-400/40 border-r-4 border-orange-100' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                
                <div class="px-6 py-2 mt-4">
                    <p class="text-orange-100 text-xs uppercase tracking-wider font-semibold">Management</p>
                </div>
                <a href="{{ route('admin.products.index') }}" 
                   class="flex items-center px-6 py-3 text-black hover:bg-orange-400/30 transition-colors {{ request()->routeIs('admin.products*') ? 'bg-orange-400/40 border-r-4 border-orange-100' : '' }}">
                    <i class="fas fa-box mr-3"></i>
                    Products
                </a>
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center px-6 py-3 text-black hover:bg-orange-400/30 transition-colors {{ request()->routeIs('admin.categories*') ? 'bg-orange-400/40 border-r-4 border-orange-100' : '' }}">
                    <i class="fas fa-tags mr-3"></i>
                    Categories
                </a>
                <a href="{{ route('admin.orders') }}" 
                   class="flex items-center px-6 py-3 text-black hover:bg-orange-400/30 transition-colors {{ request()->routeIs('admin.orders*') ? 'bg-orange-400/40 border-r-4 border-orange-100' : '' }}">
                    <i class="fas fa-shopping-cart mr-3"></i>
                    Orders
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-6 py-3 text-black hover:bg-orange-400/30 transition-colors {{ request()->routeIs('admin.users*') ? 'bg-orange-400/40 border-r-4 border-orange-100' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>
                
                <div class="px-6 py-2 mt-4">
                    <p class="text-orange-100 text-xs uppercase tracking-wider font-semibold">Admin Tools</p>
                </div>
                <a href="{{ route('admin.register') }}" 
                   class="flex items-center px-6 py-3 text-black hover:bg-orange-400/30 transition-colors {{ request()->routeIs('admin.register*') ? 'bg-orange-400/40 border-r-4 border-orange-100' : '' }}">
                    <i class="fas fa-user-plus mr-3"></i>
                    Add Admin
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-6 border-t border-orange-300/30">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-orange-400/30 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-sm text-orange-100"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-orange-100">Administrator</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" 
                            class="flex items-center w-full px-4 py-2 text-sm text-black hover:bg-orange-400/30 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">@yield('title', 'Admin Panel')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="p-2 text-gray-400 hover:text-gray-600 relative">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-orange-400 to-red-400 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-black text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                @if(session('success'))
                    <div class="mx-6 mt-6">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ session('success') }}
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mx-6 mt-6">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ session('error') }}
                            </div>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mx-6 mt-6">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                            <div class="font-bold mb-2">Please correct the following errors:</div>
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>