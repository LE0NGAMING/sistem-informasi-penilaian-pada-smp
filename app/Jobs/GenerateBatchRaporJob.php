<?php

namespace App\Jobs;

use App\Models\Siswa;
use App\Services\RaporService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateBatchRaporJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Tentukan batas timeout job dalam detik.
     */
    public int $timeout = 600; // 10 Menit

    public function __construct(
        public int $rombelId,
        public int $semesterId
    ) {}

    public function handle(RaporService $raporService): void
    {
        Log::info("Memulai kalkulasi batch rapor untuk Rombel ID: {$this->rombelId}");

        // Ambil seluruh siswa aktif di rombel
        $siswaList = Siswa::where('rombel_id', $this->rombelId)
            ->where('status', 'aktif')
            ->get();

        foreach ($siswaList as $siswa) {
            try {
                // Kalkulasi nilai rata-rata tiap siswa
                $raporService->generateSiswaRapor($siswa->id, $this->rombelId, $this->semesterId);
            } catch (\Throwable $e) {
                Log::error("Gagal generate rapor siswa ID {$siswa->id}: " . $e->getMessage());
            }
        }

        // Setelah semua siswa di-kalkulasi, jalankan ranking otomatis dalam 1 rombel
        $raporService->calculateRombelRanking($this->rombelId, $this->semesterId);

        Log::info("Selesai kalkulasi batch & ranking untuk Rombel ID: {$this->rombelId}");
    }
}
