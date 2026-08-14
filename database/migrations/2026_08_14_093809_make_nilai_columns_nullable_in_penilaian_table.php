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
            // Ubah semua kolom nilai agar boleh NULL
            $table->decimal('nilai_harian', 5, 2)->nullable()->change();
            $table->decimal('tugas', 5, 2)->nullable()->change();
            $table->decimal('quiz', 5, 2)->nullable()->change();
            $table->decimal('uts', 5, 2)->nullable()->change();
            $table->decimal('uas', 5, 2)->nullable()->change();
            $table->decimal('nilai_pengetahuan', 5, 2)->nullable()->change();

            $table->decimal('praktik', 5, 2)->nullable()->change();
            $table->decimal('proyek', 5, 2)->nullable()->change();
            $table->decimal('portofolio', 5, 2)->nullable()->change();
            $table->decimal('nilai_keterampilan', 5, 2)->nullable()->change();

            $table->decimal('nilai_akhir', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
