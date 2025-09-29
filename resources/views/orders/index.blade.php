@extends('layouts.main')

@section('title', 'My Orders')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-food-primary">My Orders</h1>
        
        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ $order->order_number }}</h2>
                                <p class="text-gray-600 text-sm">Ordered on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                            <div class="mt-2 sm:mt-0 text-right">
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
                                <p class="text-xl font-bold text-food-primary mt-1">${{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="border-t border-gray-200 pt-4">
                            <h3 class="font-semibold text-gray-800 mb-3">Order Items</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($order->orderItems as $item)
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="w-12 h-12 bg-food-secondary rounded-lg flex items-center justify-center">
                                            <span class="text-lg">🍽️</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</h4>
                                            <p class="text-xs text-gray-600">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Delivery Info -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <h4 class="font-semibold text-gray-800">Delivery Address</h4>
                                    <p class="text-gray-600">{{ $order->shipping_address }}</p>
                                    <p class="text-gray-600">Phone: {{ $order->phone }}</p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Payment</h4>
                                    <p class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                                    <p class="text-gray-600">Status: {{ ucfirst($order->payment_status) }}</p>
                                </div>
                            </div>
                            @if($order->notes)
                                <div class="mt-3">
                                    <h4 class="font-semibold text-gray-800">Special Instructions</h4>
                                    <p class="text-gray-600">{{ $order->notes }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                @if($order->status === 'delivered')
                                    <span class="text-green-600">✅ Delivered</span>
                                @elseif($order->status === 'out_for_delivery')
                                    <span class="text-purple-600">🚚 Out for delivery</span>
                                @elseif($order->status === 'preparing')
                                    <span class="text-orange-600">👨‍🍳 Being prepared</span>
                                @else
                                    <span class="text-blue-600">📋 Order confirmed</span>
                                @endif
                            </div>
                            <div class="space-x-2">
                                <a href="{{ route('orders.show', $order) }}" 
                                   class="inline-block bg-food-accent text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-opacity-90 transition-colors">
                                    View Details
                                </a>
                                @if($order->status === 'delivered')
                                    <button class="inline-block bg-food-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-opacity-90 transition-colors">
                                        Reorder
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-food-light rounded-full mb-4">
                    <span class="text-4xl">🛒</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">No orders yet</h2>
                <p class="text-gray-600 mb-8">You haven't placed any orders yet. Start browsing our delicious menu!</p>
                <a href="{{ route('home') }}" 
                   class="inline-block bg-food-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-colors duration-200">
                    Browse Menu
                </a>
            </div>
        @endif
    </div>
</div>
@endsection