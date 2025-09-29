<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function dashboard(): View
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'total_admins' => User::where('is_admin', true)->count(),
                'total_customers' => User::where('is_admin', false)->count(),
                'recent_users' => User::latest()->take(5)->get(),
            ];

            return view('admin.dashboard', compact('stats'));
        } catch (\Exception $e) {
            // If there's an error with stats, show simple view
            return view('admin.dashboard-simple');
        }
    }

    /**
     * Show products management page
     */
    public function products(): View
    {
        return view('admin.products.index');
    }

    /**
     * Show orders management page
     */
    public function orders(): View
    {
        return view('admin.orders.index');
    }

    /**
     * Show categories management page
     */
    public function categories(): View
    {
        return view('admin.categories.index');
    }

    /**
     * Show users management page
     */
    public function users(): View
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }
}
