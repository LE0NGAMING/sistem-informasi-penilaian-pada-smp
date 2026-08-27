<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToSekolah;


class Mapel extends Model
{
    use HasFactory, BelongsToSekolah;

    protected $table = 'mapel';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kelompok', // Kelompok A (Wajib), Kelompok B (Seni/Penjas), Mulok
        'urutan',
    ];

    /**
     * Relasi ke data Penilaian.
     */
    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'mapel_id');
    }

    /**
     * Relasi ke data KKM per mata pelajaran.
     */
    public function kkm(): HasMany
    {
        return $this->hasMany(KKM::class, 'mapel_id');
    }
}
