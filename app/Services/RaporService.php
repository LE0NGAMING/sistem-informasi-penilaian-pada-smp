<?php

namespace App\Services;

use App\Interfaces\PenilaianRepositoryInterface;
use App\Interfaces\RaporRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

readonly class RaporService
{
    public function __construct(
        private RaporRepositoryInterface $raporRepository,
        private PenilaianRepositoryInterface $penilaianRepository
    ) {}

    /**
     * Kalkulasi otomatis rapor untuk seorang siswa pada satu semester
     */
    public function generateSiswaRapor(int $siswaId, int $rombelId, int $semesterId): \Illuminate\Database\Eloquent\Model
    {
        return DB::transaction(function () use ($siswaId, $rombelId, $semesterId) {
            $penilaianSiswa = $this->penilaianRepository->getBySiswaAndSemester($siswaId, $semesterId);

            $rataRata = 0;
            if ($penilaianSiswa->count() > 0) {
                $rataRata = round($penilaianSiswa->avg('nilai_akhir'), 2);
            }

            $raporData = [
                'siswa_id' => $siswaId,
                'rombel_id' => $rombelId,
                'semester_id' => $semesterId,
                'rata_rata' => $rataRata,
                'status_validasi' => 'draft',
                'qr_code_signature' => (string) Str::uuid(), // Security QR Signature
            ];

            $raporEksisting = $this->raporRepository->findBySiswaAndSemester($siswaId, $semesterId);

            if ($raporEksisting) {
                $this->raporRepository->update($raporEksisting->id, $raporData);
                return $this->raporRepository->findById($raporEksisting->id);
            }

            return $this->raporRepository->create($raporData);
        });
    }

    /**
     * Kalkulasi otomatis ranking dalam 1 rombel
     */
    public function calculateRombelRanking(int $rombelId, int $semesterId): bool
    {
        $raporRombel = $this->raporRepository->getByRombelAndSemester($rombelId, $semesterId);

        $rankingData = [];
        $currentRank = 1;

        foreach ($raporRombel as $rapor) {
            $rankingData[] = [
                'id' => $rapor->id,
                'ranking' => $currentRank
            ];
            $currentRank++;
        }

        return $this->raporRepository->updateBulkRanking($rankingData);
    }
}
