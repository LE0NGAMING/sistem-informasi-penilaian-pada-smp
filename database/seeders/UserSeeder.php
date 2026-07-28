<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        User::create([
            'name' => 'System Super Admin',
            'email' => 'admin@smpn1digital.sch.id',
            'password' => Hash::make('Admin#2026!Secure'),
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // 2. Admin Sekolah
        User::create([
            'name' => 'Siti Rahma, S.Kom',
            'email' => 'adminsekolah@smpn1digital.sch.id',
            'password' => Hash::make('Password123!'),
            'role' => 'admin_sekolah',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // 3. Kepala Sekolah
        User::create([
            'name' => 'Dr. H. Ahmad Sanusi, M.Pd.',
            'email' => 'kepsek@smpn1digital.sch.id',
            'password' => Hash::make('Password123!'),
            'role' => 'kepala_sekolah',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
