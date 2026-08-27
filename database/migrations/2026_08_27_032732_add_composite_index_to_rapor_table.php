<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rapor', function (Blueprint $table) {
            // Index untuk pencarian & cetak massal rapor per rombel & semester
            $table->index(['rombel_id', 'semester_id'], 'idx_rapor_rombel_semester');
        });
    }

    public function down(): void
    {
        Schema::table('rapor', function (Blueprint $table) {
            $table->dropIndex('idx_rapor_rombel_semester');
        });
    }
};
