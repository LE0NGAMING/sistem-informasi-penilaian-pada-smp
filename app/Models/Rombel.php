<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rombel extends Model
{
    use HasFactory;

    protected $table = 'rombel';

    protected $fillable = [
        'nama_rombel',     // Contoh: 7-A, 8-B, 9-C
        'tingkat',        // Mengacu ke tabel kelas (Tingkat 7, 8, 9)
        'tahun_ajaran_id', // Mengacu ke tabel tahun_ajaran
        'wali_kelas_id',   // Mengacu ke tabel guru
    ];

    /**
     * Relasi ke Tingkat Kelas (Kelas 7, 8, 9).
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'tingkat');
    }

    /**
     * Relasi ke Tahun Ajaran.
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * Relasi ke Wali Kelas (Guru).
     */
    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    /**
     * Relasi ke seluruh Siswa di rombel ini.
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'rombel_id');
    }

    /**
     * Relasi ke Penilaian di rombel ini.
     */
    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'rombel_id');
    }

    /**
     * Relasi ke Rapor siswa di rombel ini.
     */
    public function rapor(): HasMany
    {
        return $this->hasMany(Rapor::class, 'rombel_id');
    }
}
