<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensi_harian', function (Blueprint $table) {
            // Index untuk pencarian presensi berdasarkan siswa & rentang tanggal
            $table->index(['siswa_id', 'tanggal'], 'idx_presensi_siswa_tgl');
        });
    }

    public function down(): void
    {
        Schema::table('presensi_harian', function (Blueprint $table) {
            $table->dropIndex('idx_presensi_siswa_tgl');
        });
    }
};
