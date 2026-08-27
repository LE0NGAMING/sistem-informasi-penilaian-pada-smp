<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            // 1. Hapus constraint foreign key terlebih dahulu
            $table->dropForeign('penilaian_semester_id_foreign');

            // 2. Ubah tipe data kolom menjadi string
            $table->string('semester_id', 20)->change();
        });
    }

    public function down(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->unsignedBigInteger('semester_id')->change();

            // Pasang kembali foreign key jika di-rollback (sesuaikan 'semesters' dengan nama tabel relasinya)
            $table->foreign('semester_id', 'penilaian_semester_id_foreign')
                ->references('id')
                ->on('semesters');
        });
    }
};
