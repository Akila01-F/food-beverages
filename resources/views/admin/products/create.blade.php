@extends('admin.layout')

@section('title', 'Create Product')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-yellow-50">
    <div class="container mx-auto px-6 py-8 max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Create New Product</h1>
                    <p class="text-gray-600 mt-2">Add a new product to your inventory</p>
                </div>
                <a href="{{ route('admin.products.index') }}" 
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-600 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Products
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Error Messages -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 overflow-hidden">
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Basic Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Basic Information</h3>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror"
                                       placeholder="Enter product name"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SKU (optional)</label>
                                <input type="text" 
                                       name="sku" 
                                       value="{{ old('sku') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('sku') border-red-500 @enderror"
                                       placeholder="Auto-generated if empty">
                                @error('sku')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate a unique SKU</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="description" 
                                          rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror"
                                          placeholder="Enter product description">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                <select name="category_id" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('category_id') border-red-500 @enderror"
                                        required>
                                    <option value="">Select Category</option>
                                    @foreach(\App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ingredients</label>
                                <input type="text" 
                                       name="ingredients" 
                                       value="{{ old('ingredients') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('ingredients') border-red-500 @enderror"
                                       placeholder="e.g., Beef patty, lettuce, tomato">
                                @error('ingredients')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Pricing & Stock -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Pricing & Stock</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price *</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                        <input type="number" 
                                               name="price" 
                                               step="0.01"
                                               min="0"
                                               value="{{ old('price') }}"
                                               class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('price') border-red-500 @enderror"
                                               placeholder="0.00"
                                               required>
                                    </div>
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Discounted Price</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                        <input type="number" 
                                               name="discounted_price" 
                                               step="0.01"
                                               min="0"
                                               value="{{ old('discounted_price') }}"
                                               class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('discounted_price') border-red-500 @enderror"
                                               placeholder="0.00">
                                    </div>
                                    @error('discounted_price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                                <input type="number" 
                                       name="stock_quantity" 
                                       min="0"
                                       value="{{ old('stock_quantity', 0) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('stock_quantity') border-red-500 @enderror"
                                       placeholder="0"
                                       required>
                                @error('stock_quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Spice Level</label>
                                    <select name="spice_level" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('spice_level') border-red-500 @enderror">
                                        <option value="">Select Level</option>
                                        <option value="mild" {{ old('spice_level') == 'mild' ? 'selected' : '' }}>Mild</option>
                                        <option value="medium" {{ old('spice_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="hot" {{ old('spice_level') == 'hot' ? 'selected' : '' }}>Hot</option>
                                        <option value="very_hot" {{ old('spice_level') == 'very_hot' ? 'selected' : '' }}>Very Hot</option>
                                    </select>
                                    @error('spice_level')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Prep Time (mins)</label>
                                    <input type="number" 
                                           name="preparation_time" 
                                           min="1"
                                           value="{{ old('preparation_time') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('preparation_time') border-red-500 @enderror"
                                           placeholder="15">
                                    @error('preparation_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Images -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-6">Product Images</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Images</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-400 transition-colors">
                                <input type="file" 
                                       name="images[]" 
                                       multiple 
                                       accept="image/*"
                                       class="hidden" 
                                       id="image-upload">
                                <label for="image-upload" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600">Click to upload images or drag and drop</p>
                                    <p class="text-sm text-gray-500 mt-2">PNG, JPG, GIF up to 5MB each</p>
                                </label>
                            </div>
                            @error('images.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-6">Settings</h3>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       id="is_active"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 focus:ring-2">
                                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Active</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_featured" 
                                       value="1"
                                       id="is_featured"
                                       {{ old('is_featured') ? 'checked' : '' }}
                                       class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 focus:ring-2">
                                <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">Featured Product</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.products.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-8 py-3 rounded-lg font-medium hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-lg">
                        <i class="fas fa-save mr-2"></i>Create Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Image upload preview (optional enhancement)
document.getElementById('image-upload').addEventListener('change', function(e) {
    const files = e.target.files;
    // You can add preview functionality here if needed
    if (files.length > 0) {
        const label = document.querySelector('label[for="image-upload"] p:first-child');
        label.textContent = `${files.length} file(s) selected`;
    }
});
</script>
@endsection