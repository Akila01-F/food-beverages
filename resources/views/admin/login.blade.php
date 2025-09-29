@extends('layouts.main')

@section('title', 'Admin Login')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-food-dark via-food-text-dark to-gray-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-food-secondary rounded-full flex items-center justify-center mb-4 border-2 border-food-accent">
                <span class="text-3xl">👑</span>
            </div>
            <h2 class="text-3xl font-bold text-red-500">Admin Portal</h2>
            <p class="mt-2 text-sm text-black-300">Secure administrator access</p>
        </div>

        <!-- Admin Login Form -->
        <div class="bg-white rounded-lg shadow-xl p-8 border border-food-light-dark">
            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200">
                    <p class="text-sm text-green-700">{{ session('status') }}</p>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200">
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-food-dark">Admin Username</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-food-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <input 
                            id="username" 
                            name="username" 
                            type="text" 
                            autocomplete="username" 
                            required 
                            value="{{ old('username') }}"
                            class="appearance-none rounded-lg relative block w-full px-12 py-3 border border-food-border placeholder-food-text-muted text-food-dark focus:outline-none focus:ring-2 focus:ring-food-secondary focus:border-transparent focus:z-10"
                            placeholder="Enter admin username">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-food-dark">Admin Password</label>
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
                            class="appearance-none rounded-lg relative block w-full px-12 py-3 border border-food-border placeholder-food-text-muted text-food-dark focus:outline-none focus:ring-2 focus:ring-food-secondary focus:border-transparent focus:z-10"
                            placeholder="Enter admin password">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-food-secondary focus:ring-food-secondary border-food-border rounded">
                        <label for="remember" class="ml-2 block text-sm text-food-text-muted">
                            Keep me signed in
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-food-secondary hover:bg-food-secondary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-food-secondary transition duration-150 ease-in-out">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-white group-hover:text-gray-100" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a5 5 0 0110 0z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        Access Admin Dashboard
                    </button>
                </div>

                <!-- Back to Site Link -->
                <div class="text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-food-text-muted hover:text-food-primary">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to main site
                    </a>
                </div>
            </form>
        </div>

        <!-- Security Notice -->
        <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <p class="text-sm text-gray-300">
                    Authorized personnel only. All activities are logged and monitored.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background-attachment: fixed;
    }
</style>
@endpush