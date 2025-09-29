@extends('layouts.main')

@section('title', 'Order Successful')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto text-center">
        <!-- Success Icon -->
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-4">
                <span class="text-4xl">✅</span>
            </div>
        </div>

        <!-- Success Message -->
        <h1 class="text-4xl font-bold text-green-600 mb-4">Order Placed Successfully!</h1>
        <p class="text-lg text-gray-600 mb-8">
            Thank you for your order! We've received your request and our kitchen team is preparing your delicious meal.
        </p>

        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 text-left">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">What happens next?</h2>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <span class="inline-block w-6 h-6 bg-food-primary text-white rounded-full text-center text-sm font-semibold">1</span>
                    <div>
                        <h3 class="font-medium text-gray-900">Order Confirmation</h3>
                        <p class="text-gray-600 text-sm">We're processing your order and will confirm it shortly.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="inline-block w-6 h-6 bg-food-primary text-white rounded-full text-center text-sm font-semibold">2</span>
                    <div>
                        <h3 class="font-medium text-gray-900">Preparation</h3>
                        <p class="text-gray-600 text-sm">Our chefs will start preparing your fresh meal.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="inline-block w-6 h-6 bg-food-primary text-white rounded-full text-center text-sm font-semibold">3</span>
                    <div>
                        <h3 class="font-medium text-gray-900">Delivery</h3>
                        <p class="text-gray-600 text-sm">Your order will be delivered within 30-45 minutes.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Info -->
        <div class="bg-food-light p-6 rounded-lg mb-8">
            <div class="flex items-center justify-center space-x-2 text-food-primary mb-2">
                <span class="text-xl">⏱️</span>
                <span class="font-semibold">Estimated Delivery: 30-45 minutes</span>
            </div>
            <p class="text-gray-600 text-sm">
                We'll notify you when your order is out for delivery
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
            @auth
                <a href="{{ route('orders.index') }}" 
                   class="inline-block bg-food-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-colors duration-200">
                    View My Orders
                </a>
            @endauth
            <a href="{{ route('home') }}" 
               class="inline-block bg-food-accent text-white px-6 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-colors duration-200">
               Continue Shopping
            </a>
        </div>

        <!-- Contact Support -->
        <div class="mt-12 p-4 bg-gray-50 rounded-lg">
            <p class="text-gray-600 text-sm">
                Need help with your order? 
                <a href="#" class="text-food-primary hover:underline font-medium">Contact Support</a>
                or call us at (555) 123-4567
            </p>
        </div>
    </div>
</div>
@endsection