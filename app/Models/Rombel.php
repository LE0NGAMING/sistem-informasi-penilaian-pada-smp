<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToSekolah;


/**
 * @property int $id
 * @property string $nama_rombel
 * @property int|string $tingkat
 * @property int $tahun_ajaran_id
 * @property int|null $wali_kelas_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\Kelas|null $kelas
 * @property-read \App\Models\TahunAjaran|null $tahunAjaran
 * @property-read \App\Models\Guru|null $waliKelas
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Siswa> $siswa
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Penilaian> $penilaian
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rapor> $rapor
 */
class Rombel extends Model
{
    use HasFactory, BelongsToSekolah;

    /**
     * Nama tabel yang terikat dengan model.
     *
     * @var string
     */
    protected $table = 'rombel';

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_rombel',
        'tingkat',
        'tahun_ajaran_id',
        'wali_kelas_id',
    ];

    /**
     * Penataan tipe data atribut (Casting) versi Laravel modern.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id'              => 'integer',
            'tahun_ajaran_id' => 'integer',
            'wali_kelas_id'   => 'integer',
        ];
    }

    /**
     * Relasi ke Tingkat Kelas (misal: Tingkat 7, 8, 9).
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'tingkat');
    }

    /**
     * Relasi ke Tahun Ajaran.
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * Relasi ke Wali Kelas (Guru).
     */
    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    /**
     * Relasi ke seluruh Siswa di rombel ini.
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'rombel_id');
    }

    /**
     * Relasi ke seluruh Penilaian di rombel ini.
     */
    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'rombel_id');
    }

    /**
     * Relasi ke seluruh Rapor siswa di rombel ini.
     */
    public function rapor(): HasMany
    {
        return $this->hasMany(Rapor::class, 'rombel_id');
    }
}
