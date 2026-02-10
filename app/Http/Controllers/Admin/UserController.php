<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function __construct()
    {
        // middleware applied via routes; remove to avoid constructor errors
    }

    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'in:user,petugas,admin'],
            'kelas' => ['nullable', 'string', 'max:255'],
            'jurusan' => ['nullable', 'string', 'max:255'],
            'nisn' => ['nullable', 'numeric'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user',
            'kelas' => $request->kelas ?? '11 PPLG 1',
            'jurusan' => $request->jurusan ?? 'PPLG',
            'nisn' => $request->nisn ?? 123456789,
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return redirect()->route('admin.users.index');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'in:user,petugas,admin'],
            'kelas' => ['nullable', 'string', 'max:255'],
            'jurusan' => ['nullable', 'string', 'max:255'],
            'nisn' => ['nullable', 'numeric'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'] ?? $user->role;
        $user->kelas = $validated['kelas'] ?? $user->kelas;
        $user->jurusan = $validated['jurusan'] ?? $user->jurusan;
        $user->nisn = $validated['nisn'] ?? $user->nisn;


        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('status', 'User deleted successfully.');
    }
}
