@extends('layouts.main')

@section('title', 'Register')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-food-cream via-food-beige to-food-light flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-12 w-12 bg-food-primary rounded-full flex items-center justify-center mb-4">
                <span class="text-2xl">🍔</span>
            </div>
            <h2 class="text-3xl font-bold text-food-dark">Join Our Family!</h2>
            <p class="mt-2 text-sm text-food-text-muted">Create your Food & Beverage account</p>
        </div>

        <!-- Register Form -->
        <div class="bg-white rounded-lg shadow-lg p-8 border border-food-light-dark">
            <form class="space-y-6" action="{{ route('auth.register') }}" method="POST">
                @csrf
                
                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-food-dark">Full Name</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-food-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input 
                            id="name" 
                            name="name" 
                            type="text" 
                            autocomplete="name" 
                            required 
                            value="{{ old('name') }}"
                            class="appearance-none rounded-lg relative block w-full px-12 py-3 border border-food-border placeholder-food-text-muted text-food-dark focus:outline-none focus:ring-2 focus:ring-food-primary focus:border-transparent focus:z-10"
                            placeholder="Enter your full name">
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-food-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-food-dark">Email Address</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-food-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            value="{{ old('email') }}"
                            class="appearance-none rounded-lg relative block w-full px-12 py-3 border border-food-border placeholder-food-text-muted text-food-dark focus:outline-none focus:ring-2 focus:ring-food-primary focus:border-transparent focus:z-10"
                            placeholder="Enter your email">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-food-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-food-dark">Password</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-food-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="new-password" 
                            required
                            class="appearance-none rounded-lg relative block w-full px-12 py-3 border border-food-border placeholder-food-text-muted text-food-dark focus:outline-none focus:ring-2 focus:ring-food-primary focus:border-transparent focus:z-10"
                            placeholder="Create a password (min. 8 characters)">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-food-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-food-dark">Confirm Password</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-food-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            autocomplete="new-password" 
                            required
                            class="appearance-none rounded-lg relative block w-full px-12 py-3 border border-food-border placeholder-food-text-muted text-food-dark focus:outline-none focus:ring-2 focus:ring-food-primary focus:border-transparent focus:z-10"
                            placeholder="Confirm your password">
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-food-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Terms & Conditions -->
                <div class="flex items-center">
                    <input 
                        id="terms" 
                        name="terms" 
                        type="checkbox" 
                        required
                        class="h-4 w-4 text-food-primary focus:ring-food-primary border-food-border rounded">
                    <label for="terms" class="ml-2 block text-sm text-food-text-muted">
                        I agree to the 
                        <a href="#" class="text-food-primary hover:text-food-primary-dark">Terms of Service</a> and 
                        <a href="#" class="text-food-primary hover:text-food-primary-dark">Privacy Policy</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-food-primary hover:bg-food-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-food-primary transition duration-150 ease-in-out">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-white group-hover:text-gray-100" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                            </svg>
                        </span>
                        Create Account
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-sm text-food-text-muted">
                        Already have an account? 
                        <a href="{{ route('auth.login') }}" class="font-medium text-food-primary hover:text-food-primary-dark">Sign in here</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Benefits -->
        <div class="bg-food-beige rounded-lg p-6 border border-food-light-dark">
            <h3 class="text-lg font-semibold text-food-dark mb-4 text-center">Why Join Us?</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                <div>
                    <span class="text-2xl mb-2 block">🚚</span>
                    <p class="text-sm text-food-text-muted">Fast Delivery</p>
                </div>
                <div>
                    <span class="text-2xl mb-2 block">🎯</span>
                    <p class="text-sm text-food-text-muted">Order Tracking</p>
                </div>
                <div>
                    <span class="text-2xl mb-2 block">⭐</span>
                    <p class="text-sm text-food-text-muted">Exclusive Deals</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
