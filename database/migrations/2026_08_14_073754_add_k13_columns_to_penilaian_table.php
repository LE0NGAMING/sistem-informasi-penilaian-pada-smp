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
        Schema::table('penilaian', function (Blueprint $table) {
            // Tambah Tahun Ajaran setelah rombel_id
            $table->foreignId('tahun_ajaran_id')->nullable()->after('rombel_id');

            // Tambah Kolom KI-3
            $table->decimal('nilai_pengetahuan', 5, 2)->default(0)->after('uas');
            $table->char('predikat_pengetahuan', 2)->default('D')->after('nilai_pengetahuan');
            $table->text('deskripsi_pengetahuan')->nullable()->after('predikat_pengetahuan');

            // Tambah Kolom KI-4
            $table->decimal('proyek', 5, 2)->default(0)->after('praktik');
            $table->decimal('portofolio', 5, 2)->default(0)->after('proyek');
            $table->decimal('nilai_keterampilan', 5, 2)->default(0)->after('portofolio');
            $table->char('predikat_keterampilan', 2)->default('D')->after('nilai_keterampilan');
            $table->text('deskripsi_keterampilan')->nullable()->after('predikat_keterampilan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            //
        });
    }
};
