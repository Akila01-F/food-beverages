@extends('layouts.main')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-food-primary">Order Details</h1>
                <p class="text-gray-600">Order #{{ $order->order_number }}</p>
            </div>
            <a href="{{ route('orders.index') }}" 
               class="bg-food-accent text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-colors">
                ← Back to Orders
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Items</h2>
                    
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center space-x-4 pb-4 border-b border-gray-200 last:border-b-0">
                                <div class="w-16 h-16 bg-food-secondary rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">🍽️</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $item->product->category->name }}</p>
                                    <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-food-primary">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                    <p class="text-sm text-gray-600">${{ number_format($item->price, 2) }} each</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Status</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm">✓</span>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Order Placed</h3>
                                <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if(in_array($order->status, ['confirmed', 'preparing', 'out_for_delivery', 'delivered']))
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm">✓</span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Order Confirmed</h3>
                                    <p class="text-sm text-gray-600">Your order has been confirmed</p>
                                </div>
                            </div>
                        @endif
                        
                        @if(in_array($order->status, ['preparing', 'out_for_delivery', 'delivered']))
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm">✓</span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Preparing</h3>
                                    <p class="text-sm text-gray-600">Our chefs are preparing your meal</p>
                                </div>
                            </div>
                        @endif
                        
                        @if(in_array($order->status, ['out_for_delivery', 'delivered']))
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm">✓</span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Out for Delivery</h3>
                                    <p class="text-sm text-gray-600">Your order is on its way</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($order->status === 'delivered')
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm">✓</span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Delivered</h3>
                                    <p class="text-sm text-gray-600">Your order has been delivered</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Status</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($order->status === 'preparing') bg-orange-100 text-orange-800
                                @elseif($order->status === 'out_for_delivery') bg-purple-100 text-purple-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment:</span>
                            <span class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Status:</span>
                            <span class="inline-block px-2 py-1 rounded-full text-sm font-medium
                                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h2>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal:</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tax:</span>
                            <span>${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold text-food-primary border-t pt-2">
                            <span>Total:</span>
                            <span>${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Delivery Information</h2>
                    
                    <div class="space-y-3">
                        <div>
                            <h3 class="font-medium text-gray-900">Address</h3>
                            <p class="text-gray-600 text-sm">{{ $order->shipping_address }}</p>
                        </div>
                        
                        <div>
                            <h3 class="font-medium text-gray-900">Phone</h3>
                            <p class="text-gray-600 text-sm">{{ $order->phone }}</p>
                        </div>
                        
                        @if($order->notes)
                            <div>
                                <h3 class="font-medium text-gray-900">Special Instructions</h3>
                                <p class="text-gray-600 text-sm">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if($order->status === 'delivered')
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Rate Your Order</h2>
                        <p class="text-gray-600 text-sm mb-4">How was your experience?</p>
                        <button class="w-full bg-food-primary text-white py-2 px-4 rounded-lg hover:bg-opacity-90 transition-colors">
                            Leave a Review
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection