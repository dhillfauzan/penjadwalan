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
        $users = User::where('role', 'guru')->with('gurus')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // Ambil guru unik berdasarkan NIP
        $gurus = Guru::with('mataPelajarans')->get()->unique('nip');
        return view('admin.users.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'nip_guru' => 'nullable|string|exists:gurus,nip'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru',
        ]);

        if ($request->filled('nip_guru')) {
            Guru::where('nip', $request->nip_guru)->update(['users_id' => $user->getKey()]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat');
    }

    public function edit(User $user)
    {
        // Ambil guru unik berdasarkan NIP
        $gurus = Guru::with('mataPelajarans')->get()->unique('nip');
        return view('admin.users.edit', compact('user', 'gurus'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->getKey().',users_id',
            'password' => 'nullable|min:6|confirmed',
            'nip_guru' => 'nullable|string|exists:gurus,nip'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Reset semua relasi guru sebelumnya
        Guru::where('users_id', $user->getKey())->update(['users_id' => null]);
        
        // Pasang ke guru-guru baru berdasarkan NIP
        if ($request->filled('nip_guru')) {
            Guru::where('nip', $request->nip_guru)->update(['users_id' => $user->getKey()]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated');
    }

    public function destroy(User $user)
    {
        // Hapus relasi guru
        Guru::where('users_id', $user->getKey())->update(['users_id' => null]);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted');
    }
}