<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KKM extends Model
{
    use HasFactory;

    protected $table = 'kkm';

    protected $fillable = [
        'mapel_id',
        'tahun_ajaran_id',
        'tingkat',    // 7, 8, atau 9
        'nilai_kkm',  // Standar KKM (misal: 75.00)
    ];

    protected $casts = [
        'nilai_kkm' => 'decimal:2',
    ];

    /**
     * Relasi ke Mata Pelajaran.
     */
    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    /**
     * Relasi ke Tahun Ajaran.
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
