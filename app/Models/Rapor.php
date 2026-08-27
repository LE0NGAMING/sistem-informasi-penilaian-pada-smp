<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToSekolah;


class Rapor extends Model
{
    use HasFactory, SoftDeletes, BelongsToSekolah;

    protected $table = 'rapor';

    protected $fillable = [
        'siswa_id',
        'rombel_id',
        'semester_id',
        'rata_rata',
        'ranking',
        'catatan_wali_kelas',
        'status_keputusan',
        'status_validasi',
        'qr_code_signature',
        'signed_at',
    ];

    protected $casts = [
        'rata_rata' => 'float',
        'ranking' => 'integer',
        'signed_at' => 'datetime',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class, 'rombel_id');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
