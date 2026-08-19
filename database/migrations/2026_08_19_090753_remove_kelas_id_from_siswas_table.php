<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Hapus foreign key dulu jika sebelumnya Anda set foreign key
            $table->dropForeign(['kelas_id']);

            // Baru hapus kolomnya
            $table->dropColumn('kelas_id');
        });
    }

    public function down()
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
        });
    }
};
