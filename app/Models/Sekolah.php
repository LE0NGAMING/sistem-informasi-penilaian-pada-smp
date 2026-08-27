<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolah';

    protected $fillable = [
        'npsn',
        'nama_sekolah',
        'email',
        'telepon',
        'alamat',
        'status',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'sekolah_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'sekolah_id');
    }

    public function guru()
    {
        return $this->hasMany(Guru::class, 'sekolah_id');
    }
}
