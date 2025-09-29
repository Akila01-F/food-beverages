<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLoginForm(): View|RedirectResponse
    {
        // If user is already authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // If they're an admin, redirect to admin dashboard
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard')->with('info', 'You are already logged in.');
            }
            
            // If they're a regular user, redirect to regular dashboard
            return redirect()->route('dashboard')->with('info', 'You are already logged in.');
        }
        
        return view('admin.login');
    }

    /**
     * Handle admin login request
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by username
        $user = User::where('username', $credentials['username'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['Invalid username or password.'],
            ]);
        }

        // Check if user is admin
        if (!$user->is_admin) {
            throw ValidationException::withMessages([
                'username' => ['Access denied. Admin privileges required.'],
            ]);
        }

        // Verify password
        if (!Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Invalid username or password.'],
            ]);
        }

        // Log the user in
        Auth::login($user, $request->filled('remember'));

        // Regenerate session to prevent session fixation
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show the admin registration form 
     */
    public function showRegisterForm(): View|RedirectResponse
    {
        // If user is already authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // If they're an admin, redirect to admin dashboard
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard')->with('info', 'You are already logged in as an admin.');
            }
            
            // If they're a regular user, redirect to regular dashboard
            return redirect()->route('dashboard')->with('info', 'You are already logged in.');
        }

        // Check if there are existing admin users
        $adminCount = User::where('is_admin', true)->count();
        
        if ($adminCount > 0) {
            return redirect()->route('admin.login')
                ->with('error', 'Admin registration is only available when no admin users exist.');
        }
        
        return view('admin.register');
    }

    /**
     * Handle admin registration request (only accessible by existing admins)
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_admin'] = true;
        $validated['email_verified_at'] = now();

        $admin = User::create($validated);

        return redirect()
            ->route('admin.users.show', $admin)
            ->with('success', 'New admin user created successfully!');
    }
}