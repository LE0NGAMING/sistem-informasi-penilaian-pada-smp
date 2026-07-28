<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun',     // Contoh: "2025/2026"
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke daftar Semester dalam tahun ajaran ini.
     */
    public function semester(): HasMany
    {
        return $this->hasMany(Semester::class, 'tahun_ajaran_id');
    }

    /**
     * Relasi ke Rombel yang terdaftar di tahun ajaran ini.
     */
    public function rombel(): HasMany
    {
        return $this->hasMany(Rombel::class, 'tahun_ajaran_id');
    }

    /**
     * Relasi ke Aturan KKM per tahun ajaran.
     */
    public function kkm(): HasMany
    {
        return $this->hasMany(KKM::class, 'tahun_ajaran_id');
    }
}
