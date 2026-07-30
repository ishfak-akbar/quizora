<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['teacher', 'student']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role') && in_array($request->role, ['teacher', 'student'])) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function suspend(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot suspend an admin.');
        }

        $user->status = $user->status === 'suspended' ? 'active' : 'suspended';
        $user->save();

        $message = $user->status === 'suspended' ? 'User suspended.' : 'User activated.';
        return back()->with('success', $message);
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete an admin.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
