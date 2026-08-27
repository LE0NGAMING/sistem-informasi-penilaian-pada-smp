<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToSekolah;


class Ekstrakurikuler extends Model
{
    use HasFactory, BelongsToSekolah;

    protected $table = 'ekstrakurikuler';
    protected $guarded = ['id'];

    public function nilaiEkskul()
    {
        return $this->hasMany(NilaiEkskul::class, 'ekstrakurikuler_id');
    }
}
