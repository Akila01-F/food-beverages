<x-main-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-food-primary via-food-primary-dark to-food-secondary text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-6xl font-bold mb-4 animate-bounce-slow drop-shadow-lg">Delicious Food Delivered</h1>
            <p class="text-xl mb-8 drop-shadow">From burgers to beverages, we've got your cravings covered!</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('products.index') }}" class="bg-white text-food-primary px-8 py-4 rounded-lg font-semibold hover:bg-food-light shadow-lg transition transform hover:scale-105">
                    🍽️ Order Now
                </a>
                @guest
                <a href="{{ route('auth.register') }}" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-food-primary transition shadow-lg">
                    Join Now
                </a>
                @endguest
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="max-w-7xl mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-food-dark mb-4">Browse by Category</h2>
            <p class="text-lg text-gray-600">Discover our amazing collection of delicious food and beverages</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @forelse($categories ?? [] as $category)
                <div class="group cursor-default">
                    <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transition transform hover:scale-105 group-hover:bg-food-cream border border-food-light-dark">
                        <div class="text-5xl mb-4 group-hover:animate-bounce">
                            @switch($category->slug)
                                @case('burgers') 🍔 @break
                                @case('pizza') 🍕 @break
                                @case('chinese') 🥢 @break
                                @case('snacks') 🍿 @break
                                @case('rice-and-curry') 🍛 @break
                                @case('beverages') 🥤 @break
                                @default 🍽️
                            @endswitch
                        </div>
                        <h4 class="font-semibold text-food-dark group-hover:text-food-primary transition">{{ $category->name }}</h4>
                        <p class="text-sm text-food-text-muted mt-2">{{ Str::limit($category->description, 50) }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center bg-food-beige rounded-lg p-8 border border-food-light-dark">
                    <div class="text-6xl mb-4">🍽️</div>
                    <p class="text-xl text-food-dark mb-2">No categories available yet!</p>
                    <p class="text-food-text-muted mb-4">Seed some data to get started:</p>
                    <code class="block text-sm bg-white p-3 rounded border border-food-border text-food-dark shadow-sm">php artisan db:seed --class=CategorySeeder</code>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Featured Products -->
    @if(isset($featuredProducts) && $featuredProducts->count() > 0)
    <div class="bg-food-cream py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-food-dark mb-4">Featured Dishes</h2>
                <p class="text-lg text-gray-600">Try our chef's special recommendations</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:scale-105">
                        <div class="h-48 bg-gray-200 overflow-hidden">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-food-secondary to-food-primary flex items-center justify-center">
                                    <span class="text-6xl">🍽️</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-food-dark">{{ $product->name }}</h3>
                                <span class="bg-food-primary text-white text-xs px-2 py-1 rounded-full">Featured</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($product->description, 80) }}</p>
                            <div class="flex justify-between items-center">
                                <div class="text-2xl font-bold text-food-primary">${{ number_format($product->final_price, 2) }}</div>
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-food-primary text-white px-4 py-2 rounded-lg hover:bg-food-warning transition">
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Features Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-food-dark mb-4">Why Choose Us?</h2>
                <p class="text-lg text-gray-600">Experience the best food delivery service</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center group">
                    <div class="bg-food-cream p-6 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center group-hover:bg-food-secondary transition">
                        <span class="text-4xl">🚀</span>
                    </div>
                    <h4 class="text-xl font-semibold text-food-dark mb-3">Fast Delivery</h4>
                    <p class="text-gray-600">Get your favorite food delivered in 30 minutes or less</p>
                </div>
                <div class="text-center group">
                    <div class="bg-food-cream p-6 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center group-hover:bg-food-secondary transition">
                        <span class="text-4xl">💳</span>
                    </div>
                    <h4 class="text-xl font-semibold text-food-dark mb-3">Secure Payment</h4>
                    <p class="text-gray-600">Multiple payment options with secure checkout process</p>
                </div>
                <div class="text-center group">
                    <div class="bg-food-cream p-6 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center group-hover:bg-food-secondary transition">
                        <span class="text-4xl">⭐</span>
                    </div>
                    <h4 class="text-xl font-semibold text-food-dark mb-3">Quality Food</h4>
                    <p class="text-gray-600">Fresh ingredients and amazing taste guaranteed</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="bg-gradient-to-r from-food-accent to-food-primary text-white py-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">Ready to Order?</h2>
            <p class="text-xl mb-8">Join thousands of satisfied customers and experience the best food delivery service in town!</p>
            <div class="flex justify-center">
                <a href="{{ route('products.index') }}" class="bg-white text-food-primary px-8 py-4 rounded-lg font-semibold hover:bg-food-cream transition transform hover:scale-105 shadow-lg">
                    @auth
                        🛒 Start Shopping
                    @else
                        🍽️ Browse Menu
                    @endauth
                </a>
            </div>
        </div>
    </div>
</x-main-layout>