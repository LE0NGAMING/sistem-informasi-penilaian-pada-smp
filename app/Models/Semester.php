<?php

namespace App\Models;

use App\Enums\SemesterEnum;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $table = 'semester';

    protected $fillable = [
        'tahun_ajaran_id',
        'semester',
        'is_active',
        'tanggal_pembagian_rapor',
    ];

    protected function casts(): array
    {
        return [
            'semester' => SemesterEnum::class,
            'is_active' => 'boolean',
            'tanggal_pembagian_rapor' => 'date',
        ];
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
