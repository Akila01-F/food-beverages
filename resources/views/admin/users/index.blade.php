@extends('admin.layout')

@section('title', 'Users Management')

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
                    <h1 class="text-3xl font-bold text-gray-800">Users Management</h1>
                    <p class="text-gray-600 mt-2">Manage your application users</p>
                </div>
                <div class="flex space-x-4">
                    <form method="GET" class="flex space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" onchange="this.form.submit()">
                            <option value="">All Users</option>
                            <option value="admin" {{ request('type') === 'admin' ? 'selected' : '' }}>Administrators</option>
                            <option value="customer" {{ request('type') === 'customer' ? 'selected' : '' }}>Customers</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    <a href="{{ route('admin.users.create') }}" class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-lg font-medium hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Add User
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
                        <p class="text-sm text-gray-600">Total Users</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-shield text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_admins'] }}</p>
                        <p class="text-sm text-gray-600">Administrators</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-friends text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_customers'] }}</p>
                        <p class="text-sm text-gray-600">Customers</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <form id="bulkActionForm" method="POST" action="{{ route('admin.users.bulk-action') }}" onsubmit="return confirmBulkAction()">
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
                            <option value="make_admin">Make Admin</option>
                            <option value="remove_admin">Remove Admin</option>
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
                                <th class="px-6 py-4 text-left font-medium">User</th>
                                <th class="px-6 py-4 text-left font-medium">Email</th>
                                <th class="px-6 py-4 text-left font-medium">Username</th>
                                <th class="px-6 py-4 text-left font-medium">Role</th>
                                <th class="px-6 py-4 text-left font-medium">Orders</th>
                                <th class="px-6 py-4 text-left font-medium">Joined</th>
                                <th class="px-6 py-4 text-left font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        @if($user->id !== auth()->id())
                                            <input type="checkbox" name="selected_users[]" value="{{ $user->id }}" 
                                                   class="user-checkbox rounded border-gray-300 text-orange-600">
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-r from-orange-400 to-red-400 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-white font-medium">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
                                                @if($user->id === auth()->id())
                                                    <span class="text-xs text-blue-600 font-medium">(You)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-900">{{ $user->email }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-600">{{ $user->username }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                            {{ $user->is_admin ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $user->is_admin ? 'Administrator' : 'Customer' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-900">{{ $user->orders->count() ?? 0 }} orders</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-600">{{ $user->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <a href="{{ route('admin.users.edit', $user) }}" class="text-green-600 hover:text-green-800 transition-colors" title="Edit User">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Delete User">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400" title="Cannot edit yourself">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-lg">No users found</p>
                                            <p class="text-sm mt-2">Try adjusting your search or filters</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const bulkActions = document.getElementById('bulkActions');

    selectAll.addEventListener('change', function() {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActions();
    });

    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            selectAll.checked = checkedBoxes.length === userCheckboxes.length;
            toggleBulkActions();
        });
    });

    function toggleBulkActions() {
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
        if (checkedBoxes.length > 0) {
            bulkActions.style.display = 'flex';
        } else {
            bulkActions.style.display = 'none';
        }
    }
});

function confirmBulkAction() {
    const selectedUsers = document.querySelectorAll('.user-checkbox:checked');
    const action = document.querySelector('select[name="action"]').value;
    
    if (selectedUsers.length === 0) {
        alert('Please select at least one user.');
        return false;
    }
    
    if (!action) {
        alert('Please select an action.');
        return false;
    }
    
    const actionText = {
        'make_admin': 'promote to admin',
        'remove_admin': 'remove admin privileges from',
        'delete': 'delete'
    };
    
    return confirm(`Are you sure you want to ${actionText[action]} ${selectedUsers.length} user(s)?`);
}
</script>
@endsection