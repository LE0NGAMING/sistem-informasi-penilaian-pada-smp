<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penilaian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penilaian';

    protected $fillable = [
        'siswa_id',
        'mapel_id',
        'semester_id',
        'rombel_id',
        'guru_id',
        'nilai_harian',
        'tugas',
        'quiz',
        'uts',
        'uas',
        'praktik',
        'proyek',
        'portofolio',
        'nilai_akhir',
        'predikat',
        'deskripsi',
        'is_remedial',
        'nilai_remedial',
    ];

    protected $casts = [
        'nilai_harian' => 'float',
        'tugas' => 'float',
        'quiz' => 'float',
        'uts' => 'float',
        'uas' => 'float',
        'praktik' => 'float',
        'proyek' => 'float',
        'portofolio' => 'float',
        'nilai_akhir' => 'float',
        'is_remedial' => 'boolean',
        'nilai_remedial' => 'float',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class, 'rombel_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
