@extends('layouts.main')

@section('title', $product->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-food-primary">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('products.index') }}" class="hover:text-food-primary">Menu</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('products.category', $product->category) }}" class="hover:text-food-primary">{{ $product->category->name }}</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-food-primary font-medium">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Image -->
            <div class="space-y-4">
                <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                    @if($product->images && count($product->images) > 0)
                        <img src="{{ asset('storage/' . $product->images[0]) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover"
                             id="main-image">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-food-secondary to-food-primary flex items-center justify-center">
                            <span class="text-9xl">🍽️</span>
                        </div>
                    @endif
                </div>
                @if($product->images && count($product->images) > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($product->images as $index => $image)
                        @if($index > 0 && $index < 5) {{-- Skip first image (already shown as main) and limit to 4 thumbnails --}}
                        <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden cursor-pointer hover:opacity-75 transition"
                             onclick="changeMainImage('{{ asset('storage/' . $image) }}')">
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        @endif
                    @endforeach
                    {{-- Fill remaining slots with placeholders if needed --}}
                    @for ($i = count($product->images); $i < 5; $i++)
                        <div class="aspect-square bg-food-light rounded-lg flex items-center justify-center">
                            <span class="text-2xl text-gray-400">🍽️</span>
                        </div>
                    @endfor
                </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-4xl font-bold text-food-dark mb-2">{{ $product->name }}</h1>
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="bg-food-accent text-white px-3 py-1 rounded-full text-sm font-medium">
                            {{ $product->category->name }}
                        </span>
                        @if($product->is_featured)
                            <span class="bg-food-primary text-white px-3 py-1 rounded-full text-sm font-medium">
                                ⭐ Featured
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Price -->
                <div class="space-y-2">
                    <div class="flex items-center space-x-3">
                        @if($product->discounted_price)
                            <span class="text-3xl font-bold text-food-primary">${{ number_format($product->discounted_price, 2) }}</span>
                            <span class="text-xl text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium">
                                Save ${{ number_format($product->price - $product->discounted_price, 2) }}
                            </span>
                        @else
                            <span class="text-3xl font-bold text-food-primary">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Product Details -->
                <div class="bg-food-light p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Details</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">SKU:</span>
                            <span class="text-gray-600">{{ $product->sku }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Stock:</span>
                            <span class="text-gray-600">{{ $product->stock_quantity }} available</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Spice Level:</span>
                            <span class="text-gray-600">{{ ucfirst($product->spice_level) }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Prep Time:</span>
                            <span class="text-gray-600">{{ $product->preparation_time }} minutes</span>
                        </div>
                    </div>
                </div>

                <!-- Ingredients -->
                @if($product->ingredients)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Ingredients</h3>
                        <p class="text-gray-700">{{ $product->ingredients }}</p>
                    </div>
                @endif

                <!-- Add to Cart -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <!-- Quantity Selector -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                            <div class="flex items-center space-x-3">
                                <button type="button" onclick="decreaseQuantity()" class="w-10 h-10 bg-food-light hover:bg-food-secondary text-food-dark rounded-lg flex items-center justify-center transition">
                                    <span class="text-xl">−</span>
                                </button>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity"
                                       min="1" 
                                       max="{{ $product->stock_quantity }}"
                                       value="1" 
                                       class="w-20 text-center border border-gray-300 rounded-lg py-2 focus:ring-2 focus:ring-food-accent focus:border-transparent">
                                <button type="button" onclick="increaseQuantity()" class="w-10 h-10 bg-food-light hover:bg-food-secondary text-food-dark rounded-lg flex items-center justify-center transition">
                                    <span class="text-xl">+</span>
                                </button>
                            </div>
                        </div>

                        <!-- Add to Cart Button -->
                        @if($product->stock_quantity > 0)
                            <button type="submit" 
                                    class="w-full bg-food-primary text-white py-4 px-6 rounded-lg font-semibold text-lg hover:bg-opacity-90 transition-colors duration-200 flex items-center justify-center space-x-2">
                                <span>🛒</span>
                                <span>Add to Cart</span>
                            </button>
                        @else
                            <button type="button" disabled
                                    class="w-full bg-gray-400 text-white py-4 px-6 rounded-lg font-semibold text-lg cursor-not-allowed">
                                Out of Stock
                            </button>
                        @endif
                    </form>
                </div>

                <!-- Additional Info -->
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center space-x-2">
                        <span class="text-green-600">✅</span>
                        <span class="text-gray-600">Fresh ingredients</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-blue-600">🚚</span>
                        <span class="text-gray-600">Fast delivery</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-purple-600">🔥</span>
                        <span class="text-gray-600">Made to order</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-food-dark mb-8">You might also like</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                            <a href="{{ route('products.show', $relatedProduct) }}">
                                <div class="aspect-square bg-gradient-to-br from-food-secondary to-food-primary flex items-center justify-center">
                                    <span class="text-4xl">🍽️</span>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-food-dark mb-2">{{ $relatedProduct->name }}</h3>
                                    <div class="flex items-center justify-between">
                                        @if($relatedProduct->discounted_price)
                                            <span class="text-food-primary font-bold">${{ number_format($relatedProduct->discounted_price, 2) }}</span>
                                        @else
                                            <span class="text-food-primary font-bold">${{ number_format($relatedProduct->price, 2) }}</span>
                                        @endif
                                        <span class="text-food-accent text-sm">{{ $relatedProduct->category->name }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.getAttribute('max'));
        const current = parseInt(input.value);
        if (current < max) {
            input.value = current + 1;
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const current = parseInt(input.value);
        if (current > 1) {
            input.value = current - 1;
        }
    }

    function changeMainImage(imageSrc) {
        const mainImage = document.getElementById('main-product-image');
        if (mainImage) {
            mainImage.src = imageSrc;
        }
    }
</script>
@endsection