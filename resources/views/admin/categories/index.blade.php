@extends('admin.layout')

@section('title', 'Categories Management')

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
                    <h1 class="text-3xl font-bold text-gray-800">Categories Management</h1>
                    <p class="text-gray-600 mt-2">Manage your product categories</p>
                </div>
                <div class="flex space-x-4">
                    <form method="GET" class="flex space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    <a href="{{ route('admin.categories.create') }}" class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-lg font-medium hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>Add Category
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tags text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_categories'] }}</p>
                        <p class="text-sm text-gray-600">Total Categories</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['active_categories'] }}</p>
                        <p class="text-sm text-gray-600">Active Categories</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['inactive_categories'] }}</p>
                        <p class="text-sm text-gray-600">Inactive Categories</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <form id="bulkActionForm" method="POST" action="{{ route('admin.categories.bulk-action') }}" onsubmit="return confirmBulkAction()">
            @csrf
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <label for="selectAll" class="text-sm text-gray-600">Select All</label>
                    </div>
                    <div class="flex items-center space-x-2" id="bulkActions" style="display: none;">
                        <select name="action" class="px-3 py-1 border border-gray-300 rounded text-sm">
                            <option value="">Choose Action</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button type="submit" class="px-4 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">
                            Apply
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-orange-500 to-red-500 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left font-medium">
                                    <input type="checkbox" class="rounded border-gray-300 text-orange-600">
                                </th>
                                <th class="px-6 py-4 text-left font-medium">Category</th>
                                <th class="px-6 py-4 text-left font-medium">Description</th>
                                <th class="px-6 py-4 text-left font-medium">Products</th>
                                <th class="px-6 py-4 text-left font-medium">Status</th>
                                <th class="px-6 py-4 text-left font-medium">Created</th>
                                <th class="px-6 py-4 text-left font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="selected_categories[]" value="{{ $category->id }}" 
                                               class="category-checkbox rounded border-gray-300 text-orange-600">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4 {{ $category->image ? '' : 'bg-gradient-to-r from-orange-400 to-red-400' }}">
                                                @if($category->image)
                                                    <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-12 h-12 object-cover rounded-lg">
                                                @else
                                                    <i class="fas fa-tags text-white text-xl"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900">{{ $category->name }}</h3>
                                                <p class="text-sm text-gray-500">{{ $category->slug }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-gray-600 text-sm">{{ $category->description ? Str::limit($category->description, 100) : 'No description' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-900 font-medium">{{ $category->products_count }}</span>
                                        <span class="text-gray-500 text-sm">products</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                            {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-600">{{ $category->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.categories.show', $category) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-green-600 hover:text-green-800 transition-colors" title="Edit Category">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-800 transition-colors" title="Toggle Status">
                                                    <i class="fas {{ $category->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                </button>
                                            </form>
                                            @if($category->products_count == 0)
                                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Delete Category">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400" title="Cannot delete category with products">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-tags text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-lg">No categories found</p>
                                            <p class="text-sm mt-2">Try adjusting your search or filters</p>
                                            <a href="{{ route('admin.categories.create') }}" 
                                               class="mt-4 bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-lg font-medium hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-lg">
                                                <i class="fas fa-plus mr-2"></i>Create Category
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($categories->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const bulkActions = document.getElementById('bulkActions');

    selectAll.addEventListener('change', function() {
        categoryCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActions();
    });

    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
            selectAll.checked = checkedBoxes.length === categoryCheckboxes.length;
            toggleBulkActions();
        });
    });

    function toggleBulkActions() {
        const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
        if (checkedBoxes.length > 0) {
            bulkActions.style.display = 'flex';
        } else {
            bulkActions.style.display = 'none';
        }
    }
});

function confirmBulkAction() {
    const selectedCategories = document.querySelectorAll('.category-checkbox:checked');
    const action = document.querySelector('select[name="action"]').value;
    
    if (selectedCategories.length === 0) {
        alert('Please select at least one category.');
        return false;
    }
    
    if (!action) {
        alert('Please select an action.');
        return false;
    }
    
    const actionText = {
        'activate': 'activate',
        'deactivate': 'deactivate',
        'delete': 'delete'
    };
    
    return confirm(`Are you sure you want to ${actionText[action]} ${selectedCategories.length} category(s)?`);
}
</script>

                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-orange-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Product::count() }}</p>
                            <p class="text-sm text-gray-600">Total Products</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-bar text-purple-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-gray-800">{{ number_format(\App\Models\Category::withCount('products')->get()->avg('products_count'), 1) }}</p>
                            <p class="text-sm text-gray-600">Avg Products per Category</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection