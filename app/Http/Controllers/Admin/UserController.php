<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private function checkAdmin()
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Unauthorized access.');
    }

    public function index(Request $request)
    {
        $this->checkAdmin();
        $search = $request->input('search');

        $users = User::query()
            ->when($search, fn($q, $s) => $q->where('id', 'LIKE', "%{$s}%")
                ->orWhere('name', 'LIKE', "%{$s}%")
                ->orWhere('email', 'LIKE', "%{$s}%"))
            ->orderBy('id')
            ->paginate(15);

        return view('admin.users.index', compact('users', 'search'));
    }

    public function edit(User $user)
    {
        $this->checkAdmin();
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot change your own role here.');
        }
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->checkAdmin();
        $request->validate(['role' => 'required|in:admin,staff,doctor,patient']);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot change your own role.');
        }

        $user->update(['role' => $request->role]);
        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' role updated to {$request->role}.");
    }
}