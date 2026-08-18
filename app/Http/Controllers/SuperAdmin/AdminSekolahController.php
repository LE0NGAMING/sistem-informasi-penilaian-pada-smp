<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminSekolahController extends Controller
{
    public function index()
    {
        $adminSekolah = User::where('role', RoleEnum::ADMIN_SEKOLAH->value)
            ->latest()
            ->paginate(10);

        return view('superadmin.admin-sekolah.index', compact('adminSekolah'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'username'  => $validated['username'],
            'password'  => Hash::make($validated['password']),
            'role'      => RoleEnum::ADMIN_SEKOLAH->value,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Akun Admin Sekolah berhasil ditambahkan.');
    }

    public function update(Request $request, User $adminSekolah)
    {
        // Pastikan user yang diedit memang Admin Sekolah
        abort_if($adminSekolah->role !== RoleEnum::ADMIN_SEKOLAH, 403);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($adminSekolah->id)],
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($adminSekolah->id)],
        ]);

        $adminSekolah->update($validated);

        return redirect()->back()->with('success', 'Data Admin Sekolah berhasil diperbarui.');
    }

    public function destroy(User $adminSekolah)
    {
        abort_if($adminSekolah->role !== RoleEnum::ADMIN_SEKOLAH, 403);

        $adminSekolah->delete();

        return redirect()->back()->with('success', 'Akun Admin Sekolah berhasil dihapus.');
    }

    public function toggleStatus(User $adminSekolah)
    {
        abort_if($adminSekolah->role !== RoleEnum::ADMIN_SEKOLAH, 403);

        $adminSekolah->update([
            'is_active' => !$adminSekolah->is_active,
        ]);

        $status = $adminSekolah->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Akun Admin Sekolah berhasil {$status}.");
    }

    public function resetPassword(Request $request, User $adminSekolah)
    {
        abort_if($adminSekolah->role !== RoleEnum::ADMIN_SEKOLAH, 403);

        $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        $adminSekolah->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password Admin Sekolah berhasil di-reset.');
    }
}
