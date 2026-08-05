<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            // Tambahkan mapel_id HANYA jika belum ada di tabel
            if (!Schema::hasColumn('guru', 'mapel_id')) {
                $table->foreignId('mapel_id')->nullable()->after('foto_path')->constrained('mapel')->nullOnDelete();
            }

            // Tambahkan tanggal_mulai_mengajar HANYA jika belum ada
            if (!Schema::hasColumn('guru', 'tanggal_mulai_mengajar')) {
                $table->date('tanggal_mulai_mengajar')->nullable()->after('foto_path');
            }

            // Tambahkan tanggal_pensiun HANYA jika belum ada
            if (!Schema::hasColumn('guru', 'tanggal_pensiun')) {
                $table->date('tanggal_pensiun')->nullable()->after('tanggal_mulai_mengajar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            if (Schema::hasColumn('guru', 'tanggal_pensiun')) {
                $table->dropColumn('tanggal_pensiun');
            }
            if (Schema::hasColumn('guru', 'tanggal_mulai_mengajar')) {
                $table->dropColumn('tanggal_mulai_mengajar');
            }
        });
    }
};
