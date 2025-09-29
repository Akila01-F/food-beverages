@extends('admin.layout')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-yellow-50">
    <div class="container mx-auto px-6 py-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">User Details</h1>
                    <p class="text-gray-600 mt-2">View and manage user information</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-600 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Users
                    </a>
                    @if($user->id !== auth()->id())
                        <a href="{{ route('admin.users.edit', $user) }}" class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-lg font-medium hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-lg">
                            <i class="fas fa-edit mr-2"></i>Edit User
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- User Information Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-orange-100 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-20 h-20 bg-gradient-to-r from-orange-400 to-red-400 rounded-full flex items-center justify-center mr-6">
                                <span class="text-white font-bold text-2xl">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                                <p class="text-gray-600">{{ $user->email }}</p>
                                <div class="mt-2 flex items-center space-x-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        {{ $user->is_admin ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $user->is_admin ? 'Administrator' : 'Customer' }}
                                    </span>
                                    @if($user->id === auth()->id())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            Current User
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- User Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wider">Username</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $user->username }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wider">Email Address</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $user->email }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wider">Role</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $user->is_admin ? 'Administrator' : 'Customer' }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wider">Member Since</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $user->created_at->format('F d, Y') }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wider">Last Updated</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $user->updated_at->format('F d, Y \a\t g:i A') }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wider">Email Status</label>
                                    <p class="mt-1">
                                        @if($user->email_verified_at)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-circle mr-1"></i>Unverified
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                @if($user->orders->count() > 0)
                    <div class="mt-8 bg-white rounded-xl shadow-sm border border-orange-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($user->orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                #{{ $order->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @switch($order->status)
                                                        @case('pending')
                                                            bg-yellow-100 text-yellow-800
                                                            @break
                                                        @case('completed')
                                                            bg-green-100 text-green-800
                                                            @break
                                                        @case('cancelled')
                                                            bg-red-100 text-red-800
                                                            @break
                                                        @default
                                                            bg-gray-100 text-gray-800
                                                    @endswitch">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ${{ number_format($order->total_amount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Quick Stats Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Stats</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-shopping-cart text-blue-600 mr-3"></i>
                                <span class="text-sm font-medium text-gray-700">Total Orders</span>
                            </div>
                            <span class="text-xl font-bold text-blue-600">{{ $user->orders->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-dollar-sign text-green-600 mr-3"></i>
                                <span class="text-sm font-medium text-gray-700">Total Spent</span>
                            </div>
                            <span class="text-xl font-bold text-green-600">
                                ${{ number_format($user->orders->sum('total_amount'), 2) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-purple-600 mr-3"></i>
                                <span class="text-sm font-medium text-gray-700">Member Since</span>
                            </div>
                            <span class="text-sm font-bold text-purple-600">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                @if($user->id !== auth()->id())
                    <div class="mt-6 bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                        
                        <div class="space-y-3">
                            <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors"
                                        onclick="return confirm('Are you sure you want to {{ $user->is_admin ? 'remove admin privileges from' : 'promote' }} this user?')">
                                    <i class="fas {{ $user->is_admin ? 'fa-user-minus' : 'fa-user-shield' }} mr-2"></i>
                                    {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                  onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                    <i class="fas fa-trash mr-2"></i>Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection