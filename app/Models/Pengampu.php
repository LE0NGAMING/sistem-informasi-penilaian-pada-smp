<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengampu extends Model
{
    use HasFactory;

    protected $table = 'pengampu';

    protected $fillable = [
        'guru_id',
        'mapel_id',
        'rombel_id',
        'tahun_ajaran_id',
    ];

    /**
     * Relasi ke Model Guru
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Relasi ke Model Mapel
     */
    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
    }

    /**
     * Relasi ke Model Rombel (Kelas)
     */
    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class);
    }

    /**
     * Relasi ke Model Tahun Ajaran
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
