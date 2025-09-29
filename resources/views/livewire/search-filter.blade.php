<div class="space-y-6">
    <!-- Search and Filter Controls -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by name, description..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select wire:model.live="categoryId" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Price Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                <div class="flex space-x-2">
                    <input 
                        type="number" 
                        wire:model.live.debounce.500ms="minPrice"
                        placeholder="Min" 
                        class="w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        step="0.01"
                        min="0"
                    >
                    <input 
                        type="number" 
                        wire:model.live.debounce.500ms="maxPrice"
                        placeholder="Max" 
                        class="w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        step="0.01"
                        min="0"
                    >
                </div>
            </div>

            <!-- Spice Level -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Spice Level</label>
                <select wire:model.live="spiceLevel" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Any Level</option>
                    <option value="mild">Mild</option>
                    <option value="medium">Medium</option>
                    <option value="hot">Hot</option>
                </select>
            </div>
        </div>

        <!-- Sort and Clear -->
        <div class="mt-4 flex flex-wrap items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-700">Sort by:</span>
                <button 
                    wire:click="sortBy('name')"
                    class="text-sm px-3 py-1 rounded-md border {{ $sortBy === 'name' ? 'bg-blue-100 border-blue-300 text-blue-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}"
                >
                    Name
                    @if($sortBy === 'name')
                        @if($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </button>
                <button 
                    wire:click="sortBy('price')"
                    class="text-sm px-3 py-1 rounded-md border {{ $sortBy === 'price' ? 'bg-blue-100 border-blue-300 text-blue-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}"
                >
                    Price
                    @if($sortBy === 'price')
                        @if($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </button>
                <button 
                    wire:click="sortBy('created_at')"
                    class="text-sm px-3 py-1 rounded-md border {{ $sortBy === 'created_at' ? 'bg-blue-100 border-blue-300 text-blue-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}"
                >
                    Newest
                    @if($sortBy === 'created_at')
                        @if($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </button>
            </div>
            
            <button 
                wire:click="clearFilters"
                class="text-sm px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors"
            >
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Results -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <!-- Product Image -->
                <div class="relative h-48 bg-gray-200">
                    @if($product->images && count($product->images) > 0)
                        <img 
                            src="{{ asset('storage/' . $product->images[0]) }}" 
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Featured Badge -->
                    @if($product->is_featured)
                        <div class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 px-2 py-1 rounded-full text-xs font-semibold">
                            Featured
                        </div>
                    @endif

                    <!-- Spice Level -->
                    @if($product->spice_level)
                        <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs">
                            {{ ucfirst($product->spice_level) }}
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $product->name }}</h3>
                    
                    <!-- Category -->
                    <div class="text-sm text-gray-500 mb-2">{{ $product->category->name }}</div>
                    
                    <!-- Price -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            @if($product->discounted_price)
                                <span class="font-bold text-lg text-green-600">${{ number_format($product->discounted_price, 2) }}</span>
                                <span class="text-sm text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="font-bold text-lg text-gray-900">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        
                        <!-- Stock Status -->
                        @if($product->stock_quantity > 0)
                            <span class="text-xs text-green-600 font-medium">In Stock</span>
                        @else
                            <span class="text-xs text-red-600 font-medium">Out of Stock</span>
                        @endif
                    </div>

                    <!-- Rating -->
                    @if($product->reviews_count > 0)
                        <div class="flex items-center mb-3">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->average_rating))
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-2 text-sm text-gray-600">{{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count }})</span>
                        </div>
                    @endif

                    <!-- Description -->
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->description }}</p>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a 
                            href="{{ route('products.show', $product->slug) }}"
                            class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors"
                        >
                            View Details
                        </a>
                        
                        @if($product->stock_quantity > 0)
                            <livewire:shopping-cart :product="$product" wire:key="cart-{{ $product->id }}" />
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No products found</h3>
                <p class="mt-2 text-sm text-gray-500">
                    @if($search || $categoryId || $minPrice || $maxPrice || $spiceLevel)
                        Try adjusting your filters or search terms.
                    @else
                        No products are available at the moment.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif

    <!-- Loading indicator -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-4 shadow-lg">
            <div class="flex items-center space-x-3">
                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700">Searching products...</span>
            </div>
        </div>
    </div>
</div>