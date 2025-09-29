@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-food-cream py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-4xl">👋</span>
                </div>
                <div class="ml-4">
                    <h1 class="text-3xl font-bold text-food-dark">Welcome back, {{ auth()->user()->name }}!</h1>
                    <p class="text-food-text-muted">Ready to order some delicious food?</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('products.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-food-primary rounded-full">
                        <span class="text-2xl text-white">🍽️</span>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-food-dark">Browse Menu</h3>
                        <p class="text-food-text-muted">Explore our delicious offerings</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('cart.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-food-secondary rounded-full">
                        <span class="text-2xl text-white">🛒</span>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-food-dark">My Cart</h3>
                        <p class="text-food-text-muted">Review your selected items</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('orders.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-food-accent rounded-full">
                        <span class="text-2xl text-white">📋</span>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-food-dark">Order History</h3>
                        <p class="text-food-text-muted">Track your past orders</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Account Info -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-food-dark mb-6">Account Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-food-dark mb-1">Full Name</label>
                    <p class="text-food-text-muted">{{ auth()->user()->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-food-dark mb-1">Email Address</label>
                    <p class="text-food-text-muted">{{ auth()->user()->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-food-dark mb-1">Username</label>
                    <p class="text-food-text-muted">{{ auth()->user()->username ?? 'Not set' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-food-dark mb-1">Member Since</label>
                    <p class="text-food-text-muted">{{ auth()->user()->created_at->format('F j, Y') }}</p>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-food-light">
                <div class="flex space-x-4">
                    <a href="#" class="bg-food-primary hover:bg-food-primary-dark text-white px-4 py-2 rounded-lg transition">
                        Edit Profile
                    </a>
                    <a href="#" class="bg-food-light-dark hover:bg-gray-300 text-food-dark px-4 py-2 rounded-lg transition">
                        Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
