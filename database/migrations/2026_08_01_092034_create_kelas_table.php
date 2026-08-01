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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id(); // Primary Key auto-increment (id)
            $table->string('nama_kelas'); // Contoh: '7A', 'VIII B', '9C'
            $table->enum('tingkat', ['7', '8', '9'])->nullable(); // Tingkat SMP (7, 8, atau 9)

            // Optional: Jika tabel 'guru' sudah ada dan kamu ingin menambahkan Wali Kelas
            $table->foreignId('guru_id')->nullable()->constrained('guru')->onDelete('set null');

            // Optional: Jika kamu ingin menghubungkan ke tabel 'tahun_ajaran'
            $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajaran')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
