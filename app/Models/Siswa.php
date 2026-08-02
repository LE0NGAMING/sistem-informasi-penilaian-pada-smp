<?php

namespace App\Models;

use App\Enums\JenisKelaminEnum;
use App\Enums\StatusSiswaEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'orang_tua_id',
        'rombel_id', // Disamakan dengan MasterDataSeeder & Migration
        'nis',
        'nisn',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin', // Disamakan dari 'jk' menjadi 'jenis_kelamin'
        'agama',
        'alamat',
        'no_hp',
        'foto_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'jenis_kelamin' => JenisKelaminEnum::class,
            'status' => StatusSiswaEnum::class,
            'tanggal_lahir' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(OrangTua::class, 'orang_tua_id');
    }

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class, 'rombel_id');
    }

    // Alias jika ingin tetap memanggil $siswa->rombelAktif
    public function rombelAktif(): BelongsTo
    {
        return $this->belongsTo(Rombel::class, 'rombel_id');
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'siswa_id');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }

    public function rapor(): HasMany
    {
        return $this->hasMany(Rapor::class, 'siswa_id');
    }

    // 1. TAMBAHKAN RELASI KE KELAS
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
