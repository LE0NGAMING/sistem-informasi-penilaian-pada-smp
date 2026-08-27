<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\JenisKelaminEnum;
use App\Traits\BelongsToSekolah;


class Guru extends Model
{
    use HasFactory, SoftDeletes, BelongsToSekolah;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'gelar',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_hp',
        'foto_path',
        'mapel_id',
        'tanggal_mulai_mengajar',
        'tanggal_pensiun',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rombelWali(): HasMany
    {
        return $this->hasMany(Rombel::class, 'wali_kelas_id');
    }

    public function penilaianInputed(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'guru_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function getNamaGelarAttribute(): string
    {
        $depan = $this->gelar_depan ? $this->gelar_depan . ' ' : '';
        $belakang = $this->gelar_belakang ? ', ' . $this->gelar_belakang : '';
        return $depan . $this->nama_lengkap . $belakang;
    }

    protected function casts(): array
    {
        return [
            'jenis_kelamin' => JenisKelaminEnum::class,
        ];
    }

    protected $casts = [
        'tanggal_lahir'          => 'date',
        'tanggal_mulai_mengajar' => 'date',
        'tanggal_pensiun'        => 'date',
    ];

    public function pengampus(): HasMany
    {
        return $this->hasMany(Pengampu::class);
    }
}
