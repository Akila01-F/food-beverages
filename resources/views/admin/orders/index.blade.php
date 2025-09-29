@extends('admin.layout')

@section('title', 'Orders Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-yellow-50">
    <div class="container mx-auto px-6 py-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Orders Management</h1>
                    <p class="text-gray-600 mt-2">Track and manage customer orders</p>
                </div>
                <div class="flex space-x-4">
                    <form method="GET" class="flex space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders..." 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" onchange="this.form.submit()">
                            <option value="">All Orders</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Preparing</option>
                            <option value="out_for_delivery" {{ request('status') === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <button type="submit" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    <a href="{{ route('admin.orders.export', request()->all()) }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-3 rounded-lg font-medium hover:from-gray-600 hover:to-gray-700 transition-all duration-200">
                        <i class="fas fa-download mr-2"></i>Export
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_orders'] }}</p>
                        <p class="text-sm text-gray-600">Total Orders</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_orders'] }}</p>
                        <p class="text-sm text-gray-600">Pending</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['confirmed_orders'] }}</p>
                        <p class="text-sm text-gray-600">Confirmed</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['completed_orders'] }}</p>
                        <p class="text-sm text-gray-600">Completed</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['cancelled_orders'] }}</p>
                        <p class="text-sm text-gray-600">Cancelled</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xl font-bold text-gray-800">${{ number_format($stats['today_revenue'], 2) }}</p>
                        <p class="text-sm text-gray-600">Today's Revenue</p>
                    </div>
                </div>
            </div>
        </div>
                        <p class="text-sm text-gray-600">Delivered</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">${{ number_format(\App\Models\Order::sum('total_amount'), 0) }}</p>
                        <p class="text-sm text-gray-600">Total Revenue</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-xl shadow-sm border border-orange-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-orange-500 to-red-500 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-medium">Order #</th>
                            <th class="px-6 py-4 text-left font-medium">Customer</th>
                            <th class="px-6 py-4 text-left font-medium">Items</th>
                            <th class="px-6 py-4 text-left font-medium">Total</th>
                            <th class="px-6 py-4 text-left font-medium">Status</th>
                            <th class="px-6 py-4 text-left font-medium">Payment</th>
                            <th class="px-6 py-4 text-left font-medium">Date</th>
                            <th class="px-6 py-4 text-left font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse(\App\Models\Order::with(['user', 'orderItems'])->latest()->take(20)->get() as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-semibold text-gray-900">{{ $order->order_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-orange-400 to-red-400 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white text-xs font-medium">{{ strtoupper(substr($order->user->name, 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $order->user->name }}</h3>
                                            <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-900">{{ $order->orderItems->count() }} items</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-lg font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @switch($order->status)
                                            @case('pending') bg-yellow-100 text-yellow-800 @break
                                            @case('confirmed') bg-blue-100 text-blue-800 @break
                                            @case('preparing') bg-orange-100 text-orange-800 @break
                                            @case('out_for_delivery') bg-purple-100 text-purple-800 @break
                                            @case('delivered') bg-green-100 text-green-800 @break
                                            @case('cancelled') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @switch($order->payment_status)
                                            @case('pending') bg-yellow-100 text-yellow-800 @break
                                            @case('paid') bg-green-100 text-green-800 @break
                                            @case('failed') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <span class="text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                                        <span class="text-sm text-gray-600 block">{{ $order->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <button class="text-blue-600 hover:text-blue-800 transition-colors" title="View Order">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <select class="text-xs border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                                                title="Update Status">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                                            <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-lg">No orders found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Recent Activity</h2>
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="space-y-4">
                    @foreach(\App\Models\Order::latest()->limit(5)->get() as $recentOrder)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-orange-400 to-red-400 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-shopping-cart text-white"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Order {{ $recentOrder->order_number }}</p>
                                    <p class="text-sm text-gray-600">{{ $recentOrder->user->name }} • {{ $recentOrder->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-semibold text-gray-900">${{ number_format($recentOrder->total_amount, 2) }}</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2
                                    @switch($recentOrder->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('confirmed') bg-blue-100 text-blue-800 @break
                                        @case('preparing') bg-orange-100 text-orange-800 @break
                                        @case('delivered') bg-green-100 text-green-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $recentOrder->status)) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection