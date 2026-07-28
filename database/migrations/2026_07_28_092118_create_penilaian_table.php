<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapel')->onDelete('cascade');
            $table->foreignId('rombel_id')->constrained('rombel')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semester')->onDelete('cascade');

            // Komponen Nilai
            $table->decimal('nilai_harian', 5, 2)->default(0);
            $table->decimal('tugas', 5, 2)->default(0);
            $table->decimal('quiz', 5, 2)->default(0);
            $table->decimal('uts', 5, 2)->default(0);
            $table->decimal('uas', 5, 2)->default(0);
            $table->decimal('praktik', 5, 2)->default(0);

            // Output Otomatis
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->char('predikat', 2)->default('D'); // A, B, C, D
            $table->boolean('is_remedial')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Constraint unik agar 1 siswa hanya memiliki 1 record nilai per mapel per semester
            $table->unique(['siswa_id', 'mapel_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
