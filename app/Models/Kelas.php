<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToSekolah;


class Kelas extends Model
{
    use HasFactory, BelongsToSekolah;

    // Menentukan nama tabel secara eksplisit agar Laravel tidak otomatis mencari 'kelases'
    protected $table = 'kelas';

    // Kolom yang diizinkan untuk diisi secara massal (mass assignment)
    protected $fillable = [
        'nama_kelas',  // Contoh: '7-A', '8-B', '9-C'
        'tingkat',     // Contoh: '7', '8', '9'
        'guru_id',  // Bisa diisi nama wali kelas atau ID guru
    ];

    /**
     * Relasi One-to-Many: Satu kelas memiliki banyak siswa.
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'kelas_id', 'id');
    }

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'guru_id'); // sesuaikan 'guru_id' atau 'wali_kelas_id'
    }

    public function presensis()
    {
        return $this->hasMany(PresensiHarian::class, 'kelas_id');
    }
}
