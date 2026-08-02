<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\JenisKelaminEnum;

class Guru extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'gelar',
        //'gelar_belakang',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_hp',
        'foto_path'
        //'jabatan',
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
        'tanggal_lahir' => 'date',
    ];
}
