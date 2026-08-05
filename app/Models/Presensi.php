<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensis';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'metode',
        'keterangan',
    ];

    /**
     * Relasi ke model Siswa (Many-to-One)
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Relasi ke model Kelas (Many-to-One)
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
