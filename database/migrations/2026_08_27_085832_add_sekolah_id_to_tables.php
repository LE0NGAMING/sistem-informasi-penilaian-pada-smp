<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah sekolah_id di users (Nullable khusus Superadmin)
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'sekolah_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('sekolah_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('sekolah')
                    ->nullOnDelete();
            });
        }

        // 2. Tambah sekolah_id ke tabel-tabel transaksi/master lain
        $tables = [
            'guru',
            'siswa',
            'kelas',
            'mapel',
            'tahun_ajaran',
            'ekstrakurikuler',
            'rombel',
            'penilaian',
            'presensi_harian',
            'rapor'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'sekolah_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('sekolah_id')
                        ->nullable()
                        ->after('id')
                        ->constrained('sekolah')
                        ->cascadeOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'sekolah_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['sekolah_id']);
                $table->dropColumn('sekolah_id');
            });
        }

        $tables = [
            'guru',
            'siswa',
            'kelas',
            'mapel',
            'tahun_ajaran',
            'ekstrakurikuler',
            'rombel',
            'penilaian',
            'presensi_harian',
            'rapor'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'sekolah_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['sekolah_id']);
                    $table->dropColumn('sekolah_id');
                });
            }
        }
    }
};
