<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Index untuk tabel penilaian
        Schema::table('penilaian', function (Blueprint $table) {
            $table->index(['siswa_id', 'mapel_id', 'semester'], 'idx_penilaian_composite');
        });

        // 2. Index untuk tabel siswa
        Schema::table('siswa', function (Blueprint $table) {
            $table->index(['rombel_id', 'nama_lengkap'], 'idx_siswa_rombel_nama');
        });

        // 3. Index untuk tabel presensis (Hanya siswa_id dan tanggal)
        Schema::table('presensis', function (Blueprint $table) {
            $table->index(['siswa_id', 'tanggal'], 'idx_presensis_siswa_tgl');
        });

        // 4. Index untuk tabel guru
        Schema::table('guru', function (Blueprint $table) {
            $table->index('user_id', 'idx_guru_user');
        });

        // 5. Index untuk tabel rombel
        Schema::table('rombel', function (Blueprint $table) {
            $table->index(['kelas_id', 'tahun_ajaran_id'], 'idx_rombel_kelas_ta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropIndex('idx_penilaian_composite');
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->dropIndex('idx_siswa_rombel_nama');
        });

        Schema::table('presensis', function (Blueprint $table) {
            $table->dropIndex('idx_presensis_siswa_tgl');
        });

        Schema::table('guru', function (Blueprint $table) {
            $table->dropIndex('idx_guru_user');
        });

        Schema::table('rombel', function (Blueprint $table) {
            $table->dropIndex('idx_rombel_kelas_ta');
        });
    }
};
