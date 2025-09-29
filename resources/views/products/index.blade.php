<x-main-layout>
    <div class="bg-food-cream py-8">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-food-dark mb-2">🍽️ Our Menu</h1>
                <p class="text-food-text-muted">Discover delicious food and refreshing beverages</p>
            </div>

            <!-- Search and Filter -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8 border border-food-light-dark">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for dishes..." 
                               class="w-full border border-food-border rounded-lg px-4 py-2 focus:ring-2 focus:ring-food-primary focus:border-transparent">
                    </div>
                    
                    <!-- Category Filter -->
                    <div>
                        <select name="category" class="w-full border border-food-border rounded-lg px-4 py-2 focus:ring-2 focus:ring-food-primary focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Search Button -->
                    <div>
                        <button type="submit" class="w-full bg-food-primary text-white py-2 rounded-lg hover:bg-food-primary-dark transition shadow-sm">
                            🔍 Search
                        </button>
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="bg-food-success text-white p-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Categories Quick Filter -->
            <div class="mb-8">
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="{{ route('products.index') }}" 
                       class="px-6 py-2 rounded-full border-2 transition {{ !request('category') ? 'bg-food-primary text-white border-food-primary' : 'border-food-primary text-food-primary hover:bg-food-primary hover:text-white' }}">
                        All Items
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                           class="px-6 py-2 rounded-full border-2 transition {{ request('category') == $category->slug ? 'bg-food-primary text-white border-food-primary' : 'border-food-primary text-food-primary hover:bg-food-primary hover:text-white' }}">
                            @switch($category->slug)
                                @case('burgers') 🍔 @break
                                @case('pizza') 🍕 @break
                                @case('chinese') 🥢 @break
                                @case('snacks') 🍿 @break
                                @case('rice-and-curry') 🍛 @break
                                @case('beverages') 🥤 @break
                                @default 🍽️
                            @endswitch
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition transform hover:scale-105">
                            <!-- Product Image -->
                            <div class="h-48 bg-gray-200 overflow-hidden">
                                @if($product->images && count($product->images) > 0)
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-food-secondary to-food-primary flex items-center justify-center">
                                        <span class="text-5xl">
                                            @switch($product->category->slug)
                                                @case('burgers') 🍔 @break
                                                @case('pizza') 🍕 @break
                                                @case('chinese') 🥢 @break
                                                @case('snacks') 🍿 @break
                                                @case('rice-and-curry') 🍛 @break
                                                @case('beverages') 🥤 @break
                                                @default 🍽️
                                            @endswitch
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Product Details -->
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-food-dark text-lg">{{ $product->name }}</h3>
                                    @if($product->is_featured)
                                        <span class="bg-food-warning text-white text-xs px-2 py-1 rounded-full">Featured</span>
                                    @endif
                                </div>
                                
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($product->description, 100) }}</p>
                                
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm text-food-accent">{{ $product->category->name }}</span>
                                    @if($product->spice_level)
                                        <span class="text-sm">
                                            🌶️ {{ ucfirst($product->spice_level) }}
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Price -->
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        @if($product->has_discount)
                                            <span class="text-2xl font-bold text-food-primary">${{ number_format($product->discounted_price, 2) }}</span>
                                            <span class="text-sm text-gray-500 line-through ml-2">${{ number_format($product->price, 2) }}</span>
                                        @else
                                            <span class="text-2xl font-bold text-food-primary">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                    
                                    @if($product->preparation_time)
                                        <span class="text-sm text-gray-600">⏱️ {{ $product->preparation_time }} min</span>
                                    @endif
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full bg-food-primary text-white py-2 rounded-lg hover:bg-food-warning transition">
                                            🛒 Add to Cart
                                        </button>
                                    </form>
                                    <a href="{{ route('products.show', $product->slug) }}" class="bg-gray-200 text-food-dark px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                                        👁️
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div class="text-6xl mb-4">🔍</div>
                    <h2 class="text-2xl font-semibold text-food-dark mb-4">No products found</h2>
                    <p class="text-gray-600 mb-6">Try adjusting your search criteria or browse all categories.</p>
                    <a href="{{ route('products.index') }}" class="bg-food-primary text-white px-8 py-3 rounded-lg hover:bg-food-warning transition">
                        🍽️ View All Products
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-main-layout>