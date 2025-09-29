@extends('admin.layout')

@section('title', 'Create New Category')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-yellow-50">
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Create New Category</h1>
                    <p class="text-gray-600 mt-2">Add a new product category</p>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-600 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Categories
                </a>
            </div>
        </div>

        <!-- Create Category Form -->
        <div class="bg-white rounded-xl shadow-sm border border-orange-100 overflow-hidden">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Form Fields -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Category Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror"
                                       placeholder="Enter category name">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea name="description" id="description" rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror"
                                          placeholder="Enter category description (optional)">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Category Status
                                </label>
                                <div class="flex items-center space-x-6">
                                    <label class="flex items-center">
                                        <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} 
                                               class="form-radio text-orange-600">
                                        <span class="ml-2 text-sm text-gray-700">Active</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }} 
                                               class="form-radio text-orange-600">
                                        <span class="ml-2 text-sm text-gray-700">Inactive</span>
                                    </label>
                                </div>
                                @error('is_active')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Image Upload Sidebar -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <label class="block text-sm font-medium text-gray-700 mb-4">
                                    Category Image
                                </label>
                                
                                <div class="image-upload-container">
                                    <div id="imagePreview" class="mb-4 hidden">
                                        <img id="previewImg" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                                        <button type="button" id="removeImage" class="mt-2 px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">
                                            Remove Image
                                        </button>
                                    </div>
                                    
                                    <div id="uploadArea" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-500 transition-colors cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-600 mb-2">Click to upload category image</p>
                                        <p class="text-sm text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                    
                                    <input type="file" name="image" id="imageInput" accept="image/*" class="hidden">
                                </div>
                                
                                @error('image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Category Info -->
                    <div class="mt-8 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-orange-500 mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-gray-800">Category Guidelines:</h4>
                                <ul class="mt-2 text-sm text-gray-600 space-y-1">
                                    <li>• Choose a clear, descriptive name for your category</li>
                                    <li>• The slug will be automatically generated from the name</li>
                                    <li>• Upload a high-quality image to represent your category</li>
                                    <li>• Set status to "Active" to make the category visible to customers</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('admin.categories.index') }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-medium rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-lg">
                            <i class="fas fa-plus mr-2"></i>Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const uploadArea = document.getElementById('uploadArea');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeImage = document.getElementById('removeImage');

    // Upload area click
    uploadArea.addEventListener('click', function() {
        imageInput.click();
    });

    // Image input change
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
                uploadArea.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove image
    removeImage.addEventListener('click', function() {
        imageInput.value = '';
        imagePreview.classList.add('hidden');
        uploadArea.classList.remove('hidden');
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('border-orange-500');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-orange-500');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-orange-500');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            const event = new Event('change');
            imageInput.dispatchEvent(event);
        }
    });
});
</script>
@endsection