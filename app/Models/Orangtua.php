<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrangTua extends Model
{
    use HasFactory;

    protected $table = 'orang_tua';

    protected $fillable = [
        'user_id',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'no_hp',
        'alamat',
    ];

    /**
     * Relasi ke akun User (Auth Login Ortu).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Anak / Siswa (satu orang tua bisa memiliki lebih dari 1 siswa).
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'orang_tua_id');
    }
}
