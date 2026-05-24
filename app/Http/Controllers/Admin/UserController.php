<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'guru')->with('guru')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $gurus = Guru::all();
        return view('admin.users.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'guru_id' => 'nullable|exists:gurus,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru',
        ]);

        if ($request->guru_id) {
            $guru = Guru::find($request->guru_id);
            $guru->user_id = $user->id;
            $guru->save();
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat');
    }

    public function edit(User $user)
    {
        $gurus = Guru::all();
        return view('admin.users.edit', compact('user', 'gurus'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6|confirmed',
            'guru_id' => 'nullable|exists:gurus,id'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($request->guru_id) {
            $guru = Guru::find($request->guru_id);
            $guru->user_id = $user->id;
            $guru->save();
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated');
    }

    public function destroy(User $user)
    {
        // Hapus relasi guru
        if ($user->guru) {
            $user->guru->user_id = null;
            $user->guru->save();
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted');
    }
}