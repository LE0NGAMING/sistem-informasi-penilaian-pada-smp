<?php

namespace App\Models;

use App\Enums\SemesterEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'siswa_id',
        'mapel_id',
        'rombel_id',
        'semester',
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
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'semester'     => SemesterEnum::class,
            'nilai_harian' => 'float',
            'tugas'        => 'float',
            'quiz'         => 'float',
            'uts'          => 'float',
            'uas'          => 'float',
            'praktik'      => 'float',
            'nilai_akhir'  => 'float',
            'is_remedial'  => 'boolean',
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
}
