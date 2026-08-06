<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
     * Scope untuk mempermudah query Tahun Ajaran yang sedang Aktif.
     * Penggunaan di Controller: TahunAjaran::active()->first();
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Relasi ke daftar Semester dalam tahun ajaran ini.
     */
    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class, 'tahun_ajaran_id');
    }

    /**
     * Relasi ke Rombel yang terdaftar di tahun ajaran ini.
     */
    public function rombels(): HasMany
    {
        return $this->hasMany(Rombel::class, 'tahun_ajaran_id');
    }

    /**
     * Relasi ke Aturan KKM per tahun ajaran.
     */
    public function kkms(): HasMany
    {
        return $this->hasMany(KKM::class, 'tahun_ajaran_id');
    }
}
