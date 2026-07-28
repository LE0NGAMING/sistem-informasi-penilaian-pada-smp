<?php

namespace App\Policies;

use App\Models\Penilaian;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PenilaianPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        if ($user->isSuperAdmin() || $user->role === 'admin_sekolah') {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['guru_mapel', 'wali_kelas', 'kepala_sekolah']);
    }

    public function view(User $user, Penilaian $penilaian): bool
    {
        if ($user->role === 'guru_mapel') {
            return $penilaian->guru_id === $user->guru?->id;
        }

        if ($user->role === 'siswa') {
            return $penilaian->siswa_id === $user->siswa?->id;
        }

        if ($user->role === 'orang_tua') {
            return $penilaian->siswa->orang_tua_id === $user->orangTua?->id;
        }

        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['guru_mapel', 'wali_kelas']);
    }

    public function update(User $user, Penilaian $penilaian): bool
    {
        // Guru hanya boleh mengedit nilai yang mereka ampu
        return $user->role === 'guru_mapel' && $penilaian->guru_id === $user->guru?->id;
    }

    public function delete(User $user, Penilaian $penilaian): bool
    {
        return $user->isSuperAdmin();
    }
}
