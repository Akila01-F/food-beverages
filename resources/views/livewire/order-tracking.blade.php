<div class="space-y-6">
    <!-- Order Tracking Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Track Your Orders</h2>
        
        <!-- Quick Track Order -->
        <div class="mb-6">
            <label for="trackOrder" class="block text-sm font-medium text-gray-700 mb-2">
                Enter Order Number to Track
            </label>
            <div class="flex space-x-3">
                <input 
                    type="text" 
                    id="trackOrder"
                    wire:model="searchOrderNumber"
                    placeholder="Enter order number (e.g., ORD-12345)" 
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <button 
                    wire:click="trackOrder('{{ $searchOrderNumber }}')"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="trackOrder">Track</span>
                    <span wire:loading wire:target="trackOrder">Tracking...</span>
                </button>
            </div>
        </div>

        <!-- Order Status Summary -->
        @if(!empty($statusCounts))
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach(['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'] as $status)
                    @php $count = $statusCounts[$status] ?? 0; @endphp
                    <div class="text-center p-3 rounded-lg {{ $count > 0 ? 'bg-blue-50 border border-blue-200' : 'bg-gray-50' }}">
                        <div class="text-2xl font-bold {{ $count > 0 ? 'text-blue-600' : 'text-gray-400' }}">{{ $count }}</div>
                        <div class="text-xs text-gray-600 capitalize">{{ $status }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="preparing">Preparing</option>
                    <option value="ready">Ready</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <select wire:model.live="dateFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">Last 7 Days</option>
                    <option value="month">Last 30 Days</option>
                    <option value="3months">Last 3 Months</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Order Number</label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="searchOrderNumber"
                    placeholder="Order number..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                >
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <!-- Order Header -->
                    <div class="flex flex-wrap items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $order->order_number }}</h3>
                            <p class="text-sm text-gray-600">
                                Ordered on {{ $order->created_at->format('M j, Y \a\t g:i A') }}
                            </p>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $this->getStatusBadgeColor($order->status) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            <span class="text-lg font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-gray-500 mb-2">
                            <span>Order Progress</span>
                            <span>{{ $this->getProgressPercentage($order->status) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div 
                                class="bg-blue-600 h-2 rounded-full transition-all duration-300 ease-in-out" 
                                style="width: {{ $this->getProgressPercentage($order->status) }}%"
                            ></div>
                        </div>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="border-t pt-4 mb-4">
                        <h4 class="font-medium text-gray-900 mb-3">Items ({{ $order->orderItems->count() }})</h4>
                        <div class="space-y-2">
                            @foreach($order->orderItems->take(3) as $item)
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex-1">
                                        <span class="text-gray-900">{{ $item->product->name ?? 'Product not available' }}</span>
                                        <span class="text-gray-500 ml-2">× {{ $item->quantity }}</span>
                                    </div>
                                    <span class="text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</span>
                                </div>
                            @endforeach
                            
                            @if($order->orderItems->count() > 3)
                                <div class="text-sm text-gray-500">
                                    +{{ $order->orderItems->count() - 3 }} more items
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-2 pt-4 border-t">
                        <button 
                            wire:click="viewOrderDetails({{ $order->id }})"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors"
                        >
                            View Details
                        </button>
                        
                        @if($order->status === 'pending')
                            <button 
                                wire:click="cancelOrder({{ $order->id }})"
                                class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition-colors"
                                onclick="return confirm('Are you sure you want to cancel this order?')"
                            >
                                Cancel Order
                            </button>
                        @endif
                        
                        @if(in_array($order->status, ['delivered', 'completed']))
                            <button 
                                wire:click="reorder({{ $order->id }})"
                                class="bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700 transition-colors"
                            >
                                Reorder
                            </button>
                            
                            <button 
                                wire:click="downloadInvoice({{ $order->id }})"
                                class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700 transition-colors"
                            >
                                Download Invoice
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No orders found</h3>
                <p class="mt-2 text-sm text-gray-500">
                    @if($searchOrderNumber || $statusFilter || $dateFilter)
                        Try adjusting your search criteria or filters.
                    @else
                        You haven't placed any orders yet.
                    @endif
                </p>
                @if(!$searchOrderNumber && !$statusFilter && !$dateFilter)
                    <a 
                        href="{{ route('products.index') }}" 
                        class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors"
                    >
                        Start Shopping
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Order Details Modal -->
    @if($showOrderDetails && $selectedOrder)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" wire:click="closeOrderDetails">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-y-auto" wire:click.stop>
                <div class="sticky top-0 bg-white border-b px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-900">Order {{ $selectedOrder->order_number }}</h2>
                        <button wire:click="closeOrderDetails" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Order Status Timeline -->
                    <div>
                        <h3 class="font-medium text-gray-900 mb-4">Order Status</h3>
                        <div class="flex items-center justify-between">
                            @php
                                $statuses = ['pending', 'confirmed', 'preparing', 'ready', 'delivered'];
                                $currentIndex = array_search($selectedOrder->status, $statuses);
                                $isCancelled = $selectedOrder->status === 'cancelled';
                            @endphp
                            
                            @foreach($statuses as $index => $status)
                                <div class="flex items-center {{ $index < count($statuses) - 1 ? 'flex-1' : '' }}">
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                                            @if($isCancelled && $status === $selectedOrder->status) bg-red-500 text-white
                                            @elseif($index <= $currentIndex) bg-blue-500 text-white
                                            @else bg-gray-300 text-gray-600
                                            @endif
                                        ">
                                            @if(($index <= $currentIndex && !$isCancelled) || ($isCancelled && $status === $selectedOrder->status))
                                                ✓
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-600 mt-1 capitalize">{{ $status }}</span>
                                    </div>
                                    
                                    @if($index < count($statuses) - 1)
                                        <div class="flex-1 h-0.5 mx-4
                                            @if($index < $currentIndex && !$isCancelled) bg-blue-500
                                            @else bg-gray-300
                                            @endif
                                        "></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Order Info -->
                        <div>
                            <h3 class="font-medium text-gray-900 mb-3">Order Information</h3>
                            <dl class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Order Date:</dt>
                                    <dd class="text-gray-900">{{ $selectedOrder->created_at->format('M j, Y g:i A') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Status:</dt>
                                    <dd>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $this->getStatusBadgeColor($selectedOrder->status) }}">
                                            {{ ucfirst($selectedOrder->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Payment Status:</dt>
                                    <dd class="text-gray-900 capitalize">{{ $selectedOrder->payment_status }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Delivery Info -->
                        <div>
                            <h3 class="font-medium text-gray-900 mb-3">Delivery Information</h3>
                            <dl class="space-y-2 text-sm">
                                <div>
                                    <dt class="text-gray-600">Address:</dt>
                                    <dd class="text-gray-900 mt-1">{{ $selectedOrder->delivery_address }}</dd>
                                </div>
                                @if($selectedOrder->delivery_notes)
                                    <div>
                                        <dt class="text-gray-600">Notes:</dt>
                                        <dd class="text-gray-900 mt-1">{{ $selectedOrder->delivery_notes }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div>
                        <h3 class="font-medium text-gray-900 mb-3">Order Items</h3>
                        <div class="border rounded-lg overflow-hidden">
                            @foreach($selectedOrder->orderItems as $item)
                                <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b' : '' }}">
                                    <div class="flex items-center space-x-4">
                                        @if($item->product && $item->product->images)
                                            <img 
                                                src="{{ asset('storage/' . $item->product->images[0]) }}" 
                                                alt="{{ $item->product->name }}"
                                                class="w-12 h-12 object-cover rounded"
                                            >
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <h4 class="font-medium text-gray-900">
                                                {{ $item->product->name ?? 'Product not available' }}
                                            </h4>
                                            <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <div class="font-medium text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</div>
                                        <div class="text-sm text-gray-600">${{ number_format($item->price, 2) }} each</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Total -->
                        <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900">Total</span>
                                <span class="text-lg font-bold text-gray-900">${{ number_format($selectedOrder->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif

    <!-- Loading States -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 shadow-lg">
            <div class="flex items-center space-x-3">
                <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700">Loading...</span>
            </div>
        </div>
    </div>
</div>