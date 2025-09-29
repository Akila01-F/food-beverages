<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // Show admin login form
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // Handle admin login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        // Try to find user by username or email
        $user = User::where('username', $request->username)
                   ->orWhere('email', $request->username)
                   ->first();

        if ($user && Hash::check($request->password, $user->password) && $user->is_admin) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            
            return redirect('/admin/dashboard')->with('success', 'Welcome to the admin dashboard!');
        }

        return back()->withErrors([
            'username' => 'Invalid admin credentials.',
        ])->onlyInput('username');
    }

    // Handle admin logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/admin/login');
    }
}
