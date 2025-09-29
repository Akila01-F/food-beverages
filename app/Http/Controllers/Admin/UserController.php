<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by admin status
        if ($request->filled('type')) {
            if ($request->type === 'admin') {
                $query->where('is_admin', true);
            } elseif ($request->type === 'customer') {
                $query->where('is_admin', false);
            }
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $users = $query->paginate(15)->appends($request->all());

        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('is_admin', true)->count(),
            'total_customers' => User::where('is_admin', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        $user = User::create($validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user): View
    {
        $user->load(['orders' => function($query) {
            $query->latest()->take(5);
        }]);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting the current admin user
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User '{$userName}' deleted successfully!");
    }

    /**
     * Handle bulk actions on users
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,make_admin,remove_admin',
            'selected_users' => 'required|array|min:1',
            'selected_users.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->selected_users)->get();

        // Prevent bulk action on current user
        $currentUserId = auth()->id();
        $users = $users->reject(function($user) use ($currentUserId) {
            return $user->id === $currentUserId;
        });

        if ($users->isEmpty()) {
            return back()->with('error', 'No valid users selected for bulk action.');
        }

        switch ($request->action) {
            case 'delete':
                $count = $users->count();
                User::whereIn('id', $users->pluck('id'))->delete();
                return back()->with('success', "{$count} users deleted successfully!");

            case 'make_admin':
                User::whereIn('id', $users->pluck('id'))->update(['is_admin' => true]);
                return back()->with('success', count($users) . " users promoted to admin!");

            case 'remove_admin':
                User::whereIn('id', $users->pluck('id'))->update(['is_admin' => false]);
                return back()->with('success', count($users) . " users removed from admin role!");

            default:
                return back()->with('error', 'Invalid action selected.');
        }
    }

    /**
     * Toggle admin status of a user
     */
    public function toggleAdmin(User $user): RedirectResponse
    {
        // Prevent toggling own admin status
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own admin status!');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        $status = $user->is_admin ? 'promoted to admin' : 'removed from admin role';
        return back()->with('success', "User {$user->name} has been {$status}!");
    }
}