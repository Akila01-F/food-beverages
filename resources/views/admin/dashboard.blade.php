@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome to the Food & Beverage administration panel')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-food-primary">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-food-primary rounded-full">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-food-dark">Total Users</h3>
                    <p class="text-2xl font-bold text-food-primary">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Admins -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-food-secondary">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-food-secondary rounded-full">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-food-dark">Administrators</h3>
                    <p class="text-2xl font-bold text-food-secondary">{{ $stats['total_admins'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-food-accent">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-food-accent rounded-full">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-food-dark">Customers</h3>
                    <p class="text-2xl font-bold text-food-accent">{{ $stats['total_customers'] }}</p>
                </div>
            </div>
        </div>

        <!-- Products (Placeholder) -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-green-500 rounded-full">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-food-dark">Products</h3>
                    <p class="text-2xl font-bold text-green-500">0</p>
                    <p class="text-xs text-food-text-muted">Coming soon</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-food-dark">Recent Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-food-primary hover:text-food-primary-dark text-sm font-medium">
                    View All →
                </a>
            </div>
            <div class="space-y-3">
                @forelse($stats['recent_users'] as $user)
                    <div class="flex items-center justify-between p-3 bg-food-cream rounded-lg">
                        <div class="flex items-center">
                            <div class="h-8 w-8 bg-food-primary rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-food-dark">{{ $user->name }}</p>
                                <p class="text-xs text-food-text-muted">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs rounded-full {{ $user->is_admin ? 'bg-food-secondary text-white' : 'bg-food-beige text-food-dark' }}">
                                {{ $user->is_admin ? 'Admin' : 'Customer' }}
                            </span>
                            <p class="text-xs text-food-text-muted mt-1">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-food-text-muted text-center py-4">No users found</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-food-dark mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.products.index') }}" class="bg-food-primary hover:bg-food-primary-dark text-white p-4 rounded-lg text-center transition-colors">
                    <svg class="h-6 w-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="text-sm font-medium">Manage Products</p>
                </a>

                <a href="{{ route('admin.orders') }}" class="bg-food-secondary hover:bg-food-secondary-dark text-white p-4 rounded-lg text-center transition-colors">
                    <svg class="h-6 w-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-sm font-medium">View Orders</p>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="bg-food-accent hover:bg-orange-600 text-white p-4 rounded-lg text-center transition-colors">
                    <svg class="h-6 w-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <p class="text-sm font-medium">Categories</p>
                </a>

                <a href="{{ route('admin.users.index') }}" class="bg-food-primary hover:bg-food-primary-dark text-white p-4 rounded-lg text-center transition-colors">
                    <svg class="h-6 w-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <p class="text-sm font-medium">Manage Users</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-food-primary to-food-secondary rounded-lg shadow-md p-8 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <span class="text-4xl">🎯</span>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold">Welcome, {{ auth()->user()->name }}!</h2>
                <p class="mt-2 text-lg opacity-90">
                    You're logged in as an administrator. Use the navigation above to manage your food delivery platform.
                </p>
                <p class="mt-1 text-sm opacity-75">
                    Last login: {{ auth()->user()->updated_at->format('F j, Y \a\t g:i A') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection