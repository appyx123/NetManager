<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Impor Auth ditambahkan di sini

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'customer')->latest()->paginate(15);
        return view('superadmin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = ['super_admin', 'admin', 'marketing', 'technician'];
        return view('superadmin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:super_admin,admin,marketing,technician',
            'phone_number' => 'nullable|string',
            'is_active' => 'required|boolean',
            'area_id' => 'nullable|exists:master_areas,id',
            'marketing_code' => 'nullable|string|unique:users', 
        ]);

        // Penulisan Hash sudah disederhanakan
        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);

        return redirect()->route('superadmin.users.index')->with('success', 'Pegawai berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        if ($user->role === 'customer') {
            return redirect()->route('superadmin.users.index')->with('error', 'Akun pelanggan dikelola di menu Pelanggan.');
        }

        $roles = ['super_admin', 'admin', 'marketing', 'technician'];
        return view('superadmin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role === 'customer') {
            return redirect()->route('superadmin.users.index')->with('error', 'Akun pelanggan dikelola di menu Pelanggan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:super_admin,admin,marketing,technician',
            'phone_number' => 'nullable|string',
            'is_active' => 'required|boolean',
            'area_id' => 'nullable|exists:master_areas,id',
            'marketing_code' => 'nullable|string|unique:users,marketing_code,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('superadmin.users.index')->with('success', 'Pegawai berhasil diperbarui');
    }

    public function resetPassword(User $user)
    {
        if ($user->role === 'customer') {
            return redirect()->back()->with('error', 'Password pelanggan tidak dikelola di sini.');
        }

        $newPassword = 'temp' . rand(10000, 99999);
        $user->update(['password' => Hash::make($newPassword)]);

        return redirect()->back()->with('success', "Kata sandi berhasil direset. Kata sandi sementara: $newPassword");
    }

    public function destroy(User $user)
    {
        if ($user->role === 'customer') {
            return redirect()->route('superadmin.users.index')->with('error', 'Akun pelanggan tidak dapat dihapus melalui menu staf.');
        }

        // 1. Proteksi Akun Master (Permanen)
        if ($user->id === 1) {
            return redirect()->route('superadmin.users.index')->with('error', 'Gagal! Akun Master Super Admin bersifat permanen dan tidak dapat dihapus.');
        }

        // 2. Proteksi agar user tidak bisa menghapus akun yang sedang dipakai
        if ($user->id === Auth::id()) {
            return redirect()->route('superadmin.users.index')->with('error', 'Gagal! Anda tidak dapat menghapus akun yang sedang Anda gunakan saat ini.');
        }

        $user->delete();
        return redirect()->route('superadmin.users.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}