@extends('admin.layout')

@section('title', 'Product Details')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-yellow-50">
    <div class="container mx-auto px-6 py-8 max-w-6xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Product Details</h1>
                    <p class="text-gray-600 mt-2">View product information and specifications</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="bg-orange-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-orange-600 transition-all duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit Product
                    </a>
                    <a href="{{ route('admin.products.index') }}" 
                       class="bg-gray-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-600 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Products
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Product Images -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-orange-100 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Product Images</h3>
                        <div class="space-y-4">
                            @if($product->images && count(is_string($product->images) ? [$product->images] : $product->images) > 0)
                                @foreach(is_string($product->images) ? [$product->images] : $product->images as $image)
                                    <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $image) }}" 
                                             alt="{{ $product->name }}"
                                             class="max-w-full max-h-full object-contain rounded-lg">
                                    </div>
                                @endforeach
                            @else
                                <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center">
                                    <div class="text-center text-gray-500">
                                        <i class="fas fa-image text-4xl mb-2"></i>
                                        <p>No images available</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Information -->
                <div class="bg-white rounded-xl shadow-sm border border-orange-100 mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status & Visibility</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Product Status:</span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Featured Product:</span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $product->is_featured ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $product->is_featured ? 'Yes' : 'No' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Stock Status:</span>
                                @if($product->stock_quantity > 10)
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        In Stock
                                    </span>
                                @elseif($product->stock_quantity > 0)
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Low Stock
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        Out of Stock
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-orange-100 overflow-hidden">
                    <div class="p-8">
                        <!-- Product Title & Basic Info -->
                        <div class="mb-8">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</h2>
                                    <p class="text-gray-600">{{ $product->description }}</p>
                                </div>
                                @if($product->category)
                                    <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">
                                        {{ $product->category->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Pricing Information -->
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="text-3xl font-bold text-gray-800">
                                    @if($product->discounted_price)
                                        <span class="text-orange-600">${{ number_format($product->discounted_price, 2) }}</span>
                                        <span class="text-lg text-gray-500 line-through ml-2">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="text-orange-600">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                @if($product->discounted_price)
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium">
                                        {{ round((($product->price - $product->discounted_price) / $product->price) * 100) }}% OFF
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Product Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Basic Information -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Product Information</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">SKU:</span>
                                        <span class="text-gray-800 font-mono">{{ $product->sku }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Stock Quantity:</span>
                                        <span class="text-gray-800 font-semibold">{{ number_format($product->stock_quantity) }}</span>
                                    </div>

                                    @if($product->spice_level)
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-gray-600 font-medium">Spice Level:</span>
                                            <span class="text-gray-800 capitalize">
                                                {{ str_replace('_', ' ', $product->spice_level) }}
                                                @if($product->spice_level == 'mild')
                                                    <span class="text-green-600">🌶️</span>
                                                @elseif($product->spice_level == 'medium')
                                                    <span class="text-yellow-600">🌶️🌶️</span>
                                                @elseif($product->spice_level == 'hot')
                                                    <span class="text-orange-600">🌶️🌶️🌶️</span>
                                                @elseif($product->spice_level == 'extra_hot')
                                                    <span class="text-red-600">🌶️🌶️🌶️🌶️</span>
                                                @endif
                                            </span>
                                        </div>
                                    @endif

                                    @if($product->preparation_time)
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-gray-600 font-medium">Prep Time:</span>
                                            <span class="text-gray-800">{{ $product->preparation_time }} minutes</span>
                                        </div>
                                    @endif

                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Created:</span>
                                        <span class="text-gray-800">{{ $product->created_at->format('M d, Y') }}</span>
                                    </div>

                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Last Updated:</span>
                                        <span class="text-gray-800">{{ $product->updated_at->format('M d, Y g:i A') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Ingredients & Description -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Additional Details</h3>
                                
                                @if($product->ingredients)
                                    <div>
                                        <h4 class="text-md font-medium text-gray-700 mb-2">Ingredients:</h4>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <p class="text-gray-700 leading-relaxed">{{ $product->ingredients }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($product->description)
                                    <div>
                                        <h4 class="text-md font-medium text-gray-700 mb-2">Full Description:</h4>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Quick Actions -->
                                <div class="mt-8">
                                    <h4 class="text-md font-medium text-gray-700 mb-4">Quick Actions:</h4>
                                    <div class="grid grid-cols-1 gap-3">
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="flex items-center justify-center bg-orange-500 text-white py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors">
                                            <i class="fas fa-edit mr-2"></i>Edit Product
                                        </a>
                                        
                                        <form action="{{ route('admin.products.destroy', $product) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this product?')"
                                              class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full flex items-center justify-center bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition-colors">
                                                <i class="fas fa-trash-alt mr-2"></i>Delete Product
                                            </button>
                                        </form>

                                        <a href="{{ url('/products/' . $product->slug) }}" 
                                           target="_blank"
                                           class="flex items-center justify-center bg-orange-500 text-white py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors">
                                            <i class="fas fa-external-link-alt mr-2"></i>View on Website
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics & Statistics (Optional - if you want to show sales data) -->
                <div class="bg-white rounded-xl shadow-sm border border-orange-100 mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Product Statistics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ number_format($product->stock_quantity) }}</div>
                                <div class="text-sm text-gray-600">Current Stock</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</div>
                                <div class="text-sm text-gray-600">Base Price</div>
                            </div>
                            <div class="text-center p-4 bg-orange-50 rounded-lg">
                                <div class="text-2xl font-bold text-orange-600">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </div>
                                <div class="text-sm text-gray-600">Status</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection