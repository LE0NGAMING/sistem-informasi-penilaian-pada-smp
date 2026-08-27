<?php

namespace App\Models;

use App\Enums\SemesterEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToSekolah;


class Penilaian extends Model
{
    use HasFactory, BelongsToSekolah;

    protected $table = 'penilaian';

    protected $fillable = [
        'siswa_id',
        'mapel_id',
        'rombel_id',
        'tahun_ajaran_id',
        'semester',

        // KI-3 (Pengetahuan)
        'nilai_harian',
        'tugas',
        'quiz',
        'uts',
        'uas',
        'nilai_pengetahuan',
        'predikat_pengetahuan',
        'deskripsi_pengetahuan',

        // KI-4 (Keterampilan)
        'praktik',
        'proyek',
        'portofolio',
        'nilai_keterampilan',
        'predikat_keterampilan',
        'deskripsi_keterampilan',

        // Field Umum & Compatibility
        'nilai_akhir',
        'predikat',
        'is_remedial',
        'catatan',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'semester'             => SemesterEnum::class,

            // Cast Float KI-3
            'nilai_harian'         => 'float',
            'tugas'                => 'float',
            'quiz'                 => 'float',
            'uts'                  => 'float',
            'uas'                  => 'float',
            'nilai_pengetahuan'    => 'float',

            // Cast Float KI-4
            'praktik'              => 'float',
            'proyek'               => 'float',
            'portofolio'           => 'float',
            'nilai_keterampilan'   => 'float',

            // Cast Field Umum
            'nilai_akhir'          => 'float',
            'is_remedial'          => 'boolean',
        ];
    }

    // ==========================================
    // RELASI MODEL
    // ==========================================

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
    }

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
