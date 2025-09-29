@extends('layouts.main')

@section('title', 'Login')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-food-cream via-food-beige to-food-light flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-12 w-12 bg-food-primary rounded-full flex items-center justify-center mb-4">
                <span class="text-2xl">🍔</span>
            </div>
            <h2 class="text-3xl font-bold text-food-dark">Welcome Back!</h2>
            <p class="mt-2 text-sm text-food-text-muted">Sign in to your Food & Beverage account</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-lg shadow-lg p-8 border border-food-light-dark">
            <form class="space-y-6" action="{{ route('auth.login') }}" method="POST">
                @csrf
                
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
                            autocomplete="current-password" 
                            required
                            class="appearance-none rounded-lg relative block w-full px-12 py-3 border border-food-border placeholder-food-text-muted text-food-dark focus:outline-none focus:ring-2 focus:ring-food-primary focus:border-transparent focus:z-10"
                            placeholder="Enter your password">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-food-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-food-primary focus:ring-food-primary border-food-border rounded">
                        <label for="remember" class="ml-2 block text-sm text-food-text-muted">Remember me</label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-food-primary hover:text-food-primary-dark">Forgot password?</a>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-food-primary hover:bg-food-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-food-primary transition duration-150 ease-in-out">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-white group-hover:text-gray-100" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        Sign In
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center">
                    <p class="text-sm text-food-text-muted">
                        Don't have an account? 
                        <a href="{{ route('auth.register') }}" class="font-medium text-food-primary hover:text-food-primary-dark">Create one now</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Additional Links -->
        <div class="text-center">
            <p class="text-xs text-food-text-muted">
                By signing in, you agree to our 
                <a href="#" class="text-food-primary hover:text-food-primary-dark">Terms of Service</a> and 
                <a href="#" class="text-food-primary hover:text-food-primary-dark">Privacy Policy</a>
            </p>
        </div>
    </div>
</div>
@endsection
