@extends('layouts.main')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-food-primary">Checkout</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-lg p-6 order-2 lg:order-1 border border-food-light-dark">
                <h2 class="text-xl font-semibold mb-4 text-food-dark">Order Summary</h2>
                
                <div class="space-y-4 mb-6">
                    @foreach($cartProducts as $item)
                        <div class="flex items-center space-x-4 pb-4 border-b border-food-light-dark">
                            <div class="w-16 h-16 bg-gradient-to-br from-food-secondary-light to-food-primary rounded-lg flex items-center justify-center shadow-sm">
                                <span class="text-2xl">🍽️</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-food-dark">{{ $item['product']->name }}</h3>
                                <p class="text-sm text-food-text-muted">Quantity: {{ $item['quantity'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-food-primary">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-2 border-t border-food-light-dark pt-4">
                    <div class="flex justify-between text-food-text-muted">
                        <span>Subtotal:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-food-text-muted">
                        <span>Tax (10%):</span>
                        <span>${{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold text-food-primary border-t border-food-light-dark pt-2">
                        <span>Total:</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="bg-white rounded-lg shadow-lg p-6 order-1 lg:order-2 border border-food-light-dark">
                <h2 class="text-xl font-semibold mb-6 text-food-dark">Delivery Information</h2>
                
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    
                    <!-- Shipping Address -->
                    <div class="mb-6">
                        <label for="shipping_address" class="block text-sm font-medium text-food-dark mb-2">
                            Delivery Address *
                        </label>
                        <textarea 
                            name="shipping_address" 
                            id="shipping_address"
                            required
                            rows="3"
                            class="w-full px-4 py-3 border border-food-border rounded-lg focus:ring-2 focus:ring-food-primary focus:border-transparent"
                            placeholder="Enter your full delivery address...">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <p class="text-food-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="mb-6">
                        <label for="phone" class="block text-sm font-medium text-food-dark mb-2">
                            Phone Number *
                        </label>
                        <input 
                            type="tel" 
                            name="phone" 
                            id="phone"
                            required
                            class="w-full px-4 py-3 border border-food-border rounded-lg focus:ring-2 focus:ring-food-primary focus:border-transparent"
                            placeholder="Enter your phone number"
                            value="{{ old('phone') }}">
                        @error('phone')
                            <p class="text-food-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-food-dark mb-3">
                            Payment Method *
                        </label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    id="cash_on_delivery" 
                                    value="cash_on_delivery"
                                    class="w-4 h-4 text-food-primary focus:ring-food-accent border-gray-300"
                                    {{ old('payment_method', 'cash_on_delivery') == 'cash_on_delivery' ? 'checked' : '' }}>
                                <label for="cash_on_delivery" class="ml-3 block text-sm font-medium text-gray-700">
                                    💵 Cash on Delivery
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    id="card" 
                                    value="card"
                                    class="w-4 h-4 text-food-primary focus:ring-food-accent border-gray-300"
                                    {{ old('payment_method') == 'card' ? 'checked' : '' }}>
                                <label for="card" class="ml-3 block text-sm font-medium text-gray-700">
                                    💳 Pay with Card (Online)
                                </label>
                            </div>
                        </div>
                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Special Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Special Instructions (Optional)
                        </label>
                        <textarea 
                            name="notes" 
                            id="notes"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-food-accent focus:border-transparent"
                            placeholder="Any special instructions for delivery or preparation...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estimated Delivery Time -->
                    <div class="bg-food-light p-4 rounded-lg mb-6">
                        <div class="flex items-center space-x-2 text-food-primary">
                            <span class="text-lg">⏱️</span>
                            <span class="font-medium">Estimated delivery: 30-45 minutes</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full bg-food-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-opacity-90 transition-colors duration-200">
                        Place Order - ${{ number_format($total, 2) }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection