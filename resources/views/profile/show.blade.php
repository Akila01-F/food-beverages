@extends('layouts.main')

@section('title', 'My Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-food-primary">My Profile</h1>
            <p class="text-food-text-muted mt-2">Manage your account information and preferences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg border border-food-light-dark p-6">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-gradient-to-r from-food-secondary to-food-primary rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        </div>
                        <h2 class="text-xl font-bold text-food-dark">{{ $user->name }}</h2>
                        <p class="text-food-text-muted">{{ $user->email }}</p>
                        @if($user->is_admin)
                            <span class="inline-block bg-gradient-to-r from-red-500 to-orange-500 text-white px-3 py-1 rounded-full text-xs font-medium mt-2">
                                Administrator
                            </span>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center text-food-text-muted">
                            <i class="fas fa-user mr-3"></i>
                            <span class="text-sm">Username: {{ $user->username }}</span>
                        </div>
                        <div class="flex items-center text-food-text-muted">
                            <i class="fas fa-calendar-alt mr-3"></i>
                            <span class="text-sm">Member since {{ $user->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex items-center text-food-text-muted">
                            <i class="fas fa-shopping-bag mr-3"></i>
                            <span class="text-sm">{{ $user->orders->count() ?? 0 }} orders placed</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Profile Information -->
                <div class="bg-white rounded-xl shadow-lg border border-food-light-dark p-6">
                    <h3 class="text-xl font-bold text-food-dark mb-6">Profile Information</h3>
                    
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-food-dark mb-2">Full Name</label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}"
                                       class="w-full px-4 py-3 border border-food-light rounded-lg focus:ring-2 focus:ring-food-primary focus:border-food-primary @error('name') border-red-500 @enderror"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-food-dark mb-2">Username</label>
                                <input type="text" 
                                       name="username" 
                                       value="{{ old('username', $user->username) }}"
                                       class="w-full px-4 py-3 border border-food-light rounded-lg focus:ring-2 focus:ring-food-primary focus:border-food-primary @error('username') border-red-500 @enderror"
                                       required>
                                @error('username')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-food-dark mb-2">Email Address</label>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}"
                                       class="w-full px-4 py-3 border border-food-light rounded-lg focus:ring-2 focus:ring-food-primary focus:border-food-primary @error('email') border-red-500 @enderror"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" 
                                    class="bg-gradient-to-r from-food-secondary to-food-primary text-white px-6 py-3 rounded-lg font-medium hover:from-food-secondary-dark hover:to-food-primary-dark transition-all duration-200 shadow-lg">
                                <i class="fas fa-save mr-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-xl shadow-lg border border-food-light-dark p-6">
                    <h3 class="text-xl font-bold text-food-dark mb-6">Change Password</h3>
                    
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-food-dark mb-2">Current Password</label>
                                <input type="password" 
                                       name="current_password" 
                                       class="w-full px-4 py-3 border border-food-light rounded-lg focus:ring-2 focus:ring-food-primary focus:border-food-primary @error('current_password') border-red-500 @enderror"
                                       required>
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-food-dark mb-2">New Password</label>
                                    <input type="password" 
                                           name="password" 
                                           class="w-full px-4 py-3 border border-food-light rounded-lg focus:ring-2 focus:ring-food-primary focus:border-food-primary @error('password') border-red-500 @enderror"
                                           required>
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-food-dark mb-2">Confirm Password</label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           class="w-full px-4 py-3 border border-food-light rounded-lg focus:ring-2 focus:ring-food-primary focus:border-food-primary"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" 
                                    class="bg-gradient-to-r from-red-500 to-orange-500 text-white px-6 py-3 rounded-lg font-medium hover:from-red-600 hover:to-orange-600 transition-all duration-200 shadow-lg">
                                <i class="fas fa-key mr-2"></i>Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg border border-food-light-dark p-6">
                    <h3 class="text-xl font-bold text-food-dark mb-6">Quick Actions</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('orders.index') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg hover:from-blue-100 hover:to-indigo-100 transition-all duration-200">
                            <i class="fas fa-shopping-bag text-blue-600 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium text-blue-800">My Orders</p>
                                <p class="text-sm text-blue-600">View order history</p>
                            </div>
                        </a>

                        <a href="{{ route('products.index') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg hover:from-green-100 hover:to-emerald-100 transition-all duration-200">
                            <i class="fas fa-utensils text-green-600 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium text-green-800">Browse Menu</p>
                                <p class="text-sm text-green-600">Explore our dishes</p>
                            </div>
                        </a>

                        <a href="{{ route('cart.index') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-lg hover:from-orange-100 hover:to-red-100 transition-all duration-200">
                            <i class="fas fa-shopping-cart text-orange-600 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium text-orange-800">My Cart</p>
                                <p class="text-sm text-orange-600">View cart items</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
