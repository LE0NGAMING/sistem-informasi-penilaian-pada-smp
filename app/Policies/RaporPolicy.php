<?php

namespace App\Policies;

use App\Models\Rapor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RaporPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return null;
    }

    public function view(User $user, Rapor $rapor): bool
    {
        if ($user->role === 'siswa') {
            return $rapor->siswa_id === $user->siswa?->id && $rapor->status_validasi === 'published';
        }

        if ($user->role === 'orang_tua') {
            return $rapor->siswa->orang_tua_id === $user->orangTua?->id && $rapor->status_validasi === 'published';
        }

        return in_array($user->role, ['admin_sekolah', 'kepala_sekolah', 'wali_kelas']);
    }

    public function generate(User $user): bool
    {
        return in_array($user->role, ['admin_sekolah', 'wali_kelas']);
    }

    public function approve(User $user, Rapor $rapor): bool
    {
        // Hanya Kepala Sekolah yang berhak approve & mensahkan TTD digital
        return $user->role === 'kepala_sekolah' && $rapor->status_validasi === 'submitted';
    }

    public function publish(User $user, Rapor $rapor): bool
    {
        return in_array($user->role, ['admin_sekolah', 'kepala_sekolah']) && $rapor->status_validasi === 'approved_kepsek';
    }
}
