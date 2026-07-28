<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('rombel_id')->constrained('rombel')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semester')->onDelete('cascade');

            // Agregat Rapor & Kehadiran
            $table->decimal('rata_rata', 5, 2)->default(0);
            $table->integer('ranking')->nullable();
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('tanpa_keterangan')->default(0);
            $table->text('catatan_wali_kelas')->nullable();

            // Validasi & Keamanan Audit
            $table->string('status_validasi')->default('draft'); // draft, approved, printed
            $table->string('qr_signature_path')->nullable();
            $table->timestamps();

            // Unique constraint per semester per siswa
            $table->unique(['siswa_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapor');
    }
};
