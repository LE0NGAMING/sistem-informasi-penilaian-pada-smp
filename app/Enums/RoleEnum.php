<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN_SEKOLAH = 'admin_sekolah';
    case KEPALA_SEKOLAH = 'kepala_sekolah';
    case KURIKULUM = 'kurikulum';
    case GURU = 'guru';
    case SISWA = 'siswa';
    case ORANG_TUA = 'orang_tua';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN_SEKOLAH => 'Admin Sekolah',
            self::KEPALA_SEKOLAH => 'Kepala Sekolah',
            self::KURIKULUM => 'Tim Kurikulum',
            self::GURU => 'Guru / Wali Kelas',
            self::SISWA => 'Siswa',
            self::ORANG_TUA => 'Orang Tua / Wali',
        };
    }

    public function canAccessNavbar(): bool
    {
        return match ($this) {
            self::SUPER_ADMIN, self::ADMIN_SEKOLAH, self::KEPALA_SEKOLAH, self::KURIKULUM, self::GURU => true,
            default => false,
        };
    }
}
