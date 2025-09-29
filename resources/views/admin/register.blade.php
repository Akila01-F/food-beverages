@extends('admin.layout')

@section('title', 'Register New Admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-yellow-50">
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Register New Admin</h1>
                    <p class="text-gray-600 mt-2">Create a new administrator account</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-600 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Users
                </a>
            </div>
        </div>

        <div class="max-w-2xl mx-auto">
            <!-- Register Admin Form -->
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 overflow-hidden">
                <div class="p-6">
                    <!-- Warning Message -->
                    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-amber-500 mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-amber-800">Important Notice</h4>
                                <p class="mt-1 text-sm text-amber-700">
                                    You are creating a new administrator account with full system access. 
                                    Please ensure this person is trusted with administrative privileges.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.register.submit') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror"
                                       placeholder="Enter admin's full name">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Username -->
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="username" id="username" value="{{ old('username') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('username') border-red-500 @enderror"
                                       placeholder="Enter unique username">
                                @error('username')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('email') border-red-500 @enderror"
                                       placeholder="Enter admin's email address">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('password') border-red-500 @enderror"
                                           placeholder="Enter secure password">
                                    <button type="button" onclick="togglePassword('password')" 
                                            class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye" id="password-eye"></i>
                                    </button>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Minimum 8 characters required</p>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                           placeholder="Confirm password">
                                    <button type="button" onclick="togglePassword('password_confirmation')" 
                                            class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Privileges Info -->
                        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-shield-alt text-red-500 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-red-800">Administrator Privileges</h4>
                                    <ul class="mt-2 text-sm text-red-700 space-y-1">
                                        <li>• Full access to admin dashboard and all management features</li>
                                        <li>• Can create, edit, and delete users, products, and categories</li>
                                        <li>• Can view and manage all orders and system data</li>
                                        <li>• Can register new admin users</li>
                                        <li>• Cannot delete their own account or remove their admin privileges</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Current Admin Info -->
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-orange-400 to-red-400 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-800">
                                        <strong>Creating as:</strong> {{ auth()->user()->name }} ({{ auth()->user()->email }})
                                    </p>
                                    <p class="text-xs text-blue-600 mt-1">
                                        Registration timestamp: {{ now()->format('F d, Y \a\t g:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('admin.users.index') }}" 
                               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white font-medium rounded-lg hover:from-red-600 hover:to-orange-600 transition-all duration-200 shadow-lg"
                                    onclick="return confirm('Are you sure you want to create this admin account? This will give the user full administrative access.')">
                                <i class="fas fa-user-shield mr-2"></i>Create Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

// Auto-generate username from name
document.getElementById('name').addEventListener('input', function() {
    const username = this.value.toLowerCase().replace(/\s+/g, '').replace(/[^a-z0-9]/g, '');
    if (username && !document.getElementById('username').value) {
        document.getElementById('username').value = username;
    }
});
</script>
@endsection