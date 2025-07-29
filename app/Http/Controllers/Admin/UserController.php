<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', __('admin.users.created_successfully'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', __('admin.users.updated_successfully'));
    }

    public function destroy(User $user)
    {
        // Prevent deleting the current user
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', __('admin.users.cannot_delete_yourself'));
        }

        // Prevent deleting if it's the only user
        if (User::count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', __('admin.users.cannot_delete_last_user'));
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', __('admin.users.deleted_successfully'));
    }

    public function changePassword()
    {
        return view('admin.users.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.change-password')
            ->with('success', __('admin.users.password_changed_successfully'));
    }
}