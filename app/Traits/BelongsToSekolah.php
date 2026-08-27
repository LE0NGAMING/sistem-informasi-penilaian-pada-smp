<?php

namespace App\Traits;

use App\Models\Sekolah;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToSekolah
{
    protected static function bootBelongsToSekolah(): void
    {
        // 1. Filter Otomatis saat Fetch/Select Data
        static::addGlobalScope('sekolah', function (Builder $builder) {
            if (Auth::check()) {
                $user = Auth::user();
                $userRole = is_object($user->role) ? $user->role->value : $user->role;

                // Jangan filter jika user adalah Super Admin
                if ($userRole !== 'super_admin' && !empty($user->sekolah_id)) {
                    $table = $builder->getModel()->getTable();
                    $builder->where("{$table}.sekolah_id", $user->sekolah_id);
                }
            }
        });

        // 2. Isi Otomatis sekolah_id saat simpan data baru (Insert)
        static::creating(function ($model) {
            if (Auth::check() && empty($model->sekolah_id) && Auth::user()->sekolah_id) {
                $model->sekolah_id = Auth::user()->sekolah_id;
            }
        });
    }

    /**
     * Relasi ke Model Sekolah
     */
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
}
