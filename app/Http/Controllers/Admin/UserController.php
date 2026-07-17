<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::withCount('tasks')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $this->authorize('updateRole', User::class);

        $role = $request->input('role');

        if (!in_array($role, ['user', 'admin', 'moderator'], true)) {
            abort(400, 'Role tidak valid.');
        }

        if ($user->id === auth()->id() && $role === 'user') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->withErrors(['role' => 'Admin terakhir tidak bisa di-demote.']);
            }
        }

        $user->role = $role;
        $user->save();

        return back()->with('success', 'Role user diperbarui.');
    }
}