<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    // Sesuaikan nama tabel jika di database memakai nama lain (misal: 'nilais')
    protected $table = 'penilaian';

    protected $fillable = [
        'siswa_id',
        'mapel_id',
        'rombel_id',
        'semester_id',
        'nilai_harian',
        'tugas',
        'quiz',
        'uts',
        'uas',
        'praktik',
        'nilai_akhir',
        'predikat',
        'is_remedial',
        'catatan',
    ];

    /**
     * Format tipe data kolom (Casting)
     */
    protected $casts = [
        'nilai_harian' => 'float',
        'tugas'        => 'float',
        'quiz'         => 'float',
        'uts'          => 'float',
        'uas'          => 'float',
        'praktik'      => 'float',
        'nilai_akhir'  => 'float',
        'is_remedial'  => 'boolean',
    ];

    // ==========================================
    // RELASI MODEL
    // ==========================================

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function rombel()
    {
        // Ganti Rombel::class dengan Kelas::class jika kamu memakai model Kelas
        return $this->belongsTo(Rombel::class, 'rombel_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
