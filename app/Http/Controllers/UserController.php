<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of users for the supervisor.
     */
    public function index(Request $request)
    {
        $this->authorize('user.manage');

        $users = User::with('roles')
            ->when($request->search, function ($query, $search) {
                $query->where('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->when($request->role, function ($query, $role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            })
            ->paginate(20)
            ->withQueryString();

        $roles = Role::all();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => $request->only(['search', 'role']),
        ]);
    }

    /**
     * Update user roles.
     */
    public function updateRoles(Request $request, User $user)
    {
        $this->authorize('user.manage');

        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $roleIds = Role::whereIn('name', $request->roles)->pluck('id');
        $user->roles()->sync($roleIds);

        return back()->with('success', 'User roles updated successfully.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        $this->authorize('user.manage');

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', 'User status updated successfully.');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        $this->authorize('user.manage');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
