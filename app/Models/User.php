<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\RoleEnum; // Import Enum
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'two_factor_secret',
        'two_factor_confirmed_at',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',

            // 1. TAMBAHKAN CASTING ROLE KE ENUM DI SINI
            'role' => RoleEnum::class,
        ];
    }

    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    public function orangTua(): HasOne
    {
        return $this->hasOne(OrangTua::class, 'user_id');
    }

    // 2. SESUAIKAN METHOD PEMERIKSAAN ROLE AGAR MENGGUNAKAN CASE ENUM
    public function isSuperAdmin(): bool
    {
        // Sesuaikan nama Case Enum milikmu (contoh: RoleEnum::SUPER_ADMIN atau RoleEnum::SUPER_ADMIN->value)
        return $this->role === RoleEnum::SUPER_ADMIN;
    }

    public function isGuru(): bool
    {
        return $this->role === RoleEnum::GURU; //RoleEnum::WALI_KELAS
    }
}
