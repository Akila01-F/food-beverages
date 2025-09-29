@extends('admin.layout')

@section('title', 'Edit User - ' . $user->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-yellow-50">
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Edit User</h1>
                    <p class="text-gray-600 mt-2">Update user information</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.users.show', $user) }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-600 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Back to User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="bg-orange-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-orange-600 transition-all duration-200">
                        <i class="fas fa-list mr-2"></i>All Users
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit User Form -->
        <div class="bg-white rounded-xl shadow-sm border border-orange-100 overflow-hidden">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror"
                                   placeholder="Enter full name">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('username') border-red-500 @enderror"
                                   placeholder="Enter username">
                            @error('username')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('email') border-red-500 @enderror"
                                   placeholder="Enter email address">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="is_admin" class="block text-sm font-medium text-gray-700 mb-2">
                                User Role <span class="text-red-500">*</span>
                            </label>
                            <select name="is_admin" id="is_admin" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('is_admin') border-red-500 @enderror"
                                    @if($user->id === auth()->id()) disabled @endif>
                                <option value="0" {{ old('is_admin', $user->is_admin) == '0' ? 'selected' : '' }}>Customer</option>
                                <option value="1" {{ old('is_admin', $user->is_admin) == '1' ? 'selected' : '' }}>Administrator</option>
                            </select>
                            @if($user->id === auth()->id())
                                <p class="mt-1 text-sm text-gray-500">You cannot change your own role</p>
                                <input type="hidden" name="is_admin" value="{{ $user->is_admin }}">
                            @endif
                            @error('is_admin')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                New Password <span class="text-gray-500">(optional)</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('password') border-red-500 @enderror"
                                       placeholder="Leave blank to keep current password">
                                <button type="button" onclick="togglePassword('password')" 
                                        class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="password-eye"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Leave blank to keep current password. Minimum 8 characters if changing.</p>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm New Password
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                       placeholder="Confirm new password">
                                <button type="button" onclick="togglePassword('password_confirmation')" 
                                        class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- User Info Card -->
                    <div class="mt-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-gradient-to-r from-orange-400 to-red-400 rounded-full flex items-center justify-center mr-4">
                                <span class="text-white font-medium">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Current User Information:</h4>
                                <div class="mt-2 text-sm text-gray-600 space-y-1">
                                    <p><strong>Member since:</strong> {{ $user->created_at->format('F d, Y') }}</p>
                                    <p><strong>Total orders:</strong> {{ $user->orders->count() }}</p>
                                    <p><strong>Last updated:</strong> {{ $user->updated_at->format('F d, Y \a\t g:i A') }}</p>
                                    @if($user->id === auth()->id())
                                        <p class="text-blue-600"><strong>Note:</strong> This is your own account</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('admin.users.show', $user) }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-medium rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-lg">
                            <i class="fas fa-save mr-2"></i>Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        @if($user->id !== auth()->id())
            <div class="mt-8 bg-red-50 border border-red-200 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-red-800 mb-4">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone
                </h3>
                <p class="text-red-700 mb-4">
                    These actions are irreversible. Please proceed with caution.
                </p>
                <div class="flex space-x-4">
                    <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors"
                                onclick="return confirm('Are you sure you want to {{ $user->is_admin ? 'remove admin privileges from' : 'promote' }} this user?')">
                            <i class="fas {{ $user->is_admin ? 'fa-user-minus' : 'fa-user-shield' }} mr-2"></i>
                            {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Delete User
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');
    
    if (field.type === 'password') {
        field.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}
</script>
@endsection