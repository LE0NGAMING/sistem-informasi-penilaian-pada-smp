<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kkm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapel_id')->constrained('mapel')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->tinyInteger('tingkat'); // 7, 8, atau 9
            $table->decimal('nilai_kkm', 5, 2)->default(75.00);
            $table->timestamps();

            // Constraint unik agar tidak ada duplikasi KKM untuk mapel & tingkat yang sama dalam 1 tahun ajaran
            $table->unique(['mapel_id', 'tahun_ajaran_id', 'tingkat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kkm');
    }
};
