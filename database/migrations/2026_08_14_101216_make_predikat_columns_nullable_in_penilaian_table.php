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
            // Ubah ketiga kolom predikat agar mengizinkan NULL
            $table->string('predikat_pengetahuan', 2)->nullable()->change();
            $table->string('predikat_keterampilan', 2)->nullable()->change();
            $table->string('predikat', 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->string('predikat_pengetahuan', 2)->nullable(false)->default('D')->change();
            $table->string('predikat_keterampilan', 2)->nullable(false)->default('D')->change();
            $table->string('predikat', 2)->nullable(false)->default('D')->change();
        });
    }
};
