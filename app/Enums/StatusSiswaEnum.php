<?php

namespace App\Enums;

enum StatusSiswaEnum: string
{
    case AKTIF = 'aktif';
    case LULUS = 'lulus';
    case PINDAH = 'pindah';
    case DROPOUT = 'do';

    public function label(): string
    {
        return match ($this) {
            self::AKTIF => 'Aktif',
            self::LULUS => 'Lulus',
            self::PINDAH => 'Pindah Sekolah',
            self::DROPOUT => 'Drop Out (DO)',
        };
    }

    // Helper untuk tampilan warna badge pada UI Bootstrap
    public function badgeClass(): string
    {
        return match ($this) {
            self::AKTIF => 'bg-success',
            self::LULUS => 'bg-primary',
            self::PINDAH => 'bg-warning text-dark',
            self::DROPOUT => 'bg-danger',
        };
    }
}
