<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    // Dashboard menampilkan jumlah user per role dan list user
    public function dashboard()
    {
        $userCount = [
            'user' => User::where('role', 'user')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'superadmin' => User::where('role', 'superadmin')->count(),
        ];

        $users = User::all(['id', 'name', 'email', 'role']);

        return view('superadmin.dashboard', compact('userCount', 'users'));
    }

    // Menambahkan user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:user,admin,superadmin',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        return redirect()->route('superadmin.dashboard')->with('success', 'User berhasil ditambahkan.');
    }

    // Mengupdate user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('superadmin.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:user,admin,superadmin',
        ]);

        $user->update($validated);

        return redirect()->route('superadmin.dashboard')->with('success', 'User berhasil diperbarui.');
    }

    // Menghapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('superadmin.dashboard')->with('success', 'User berhasil dihapus.');
    }
}
