<?php

namespace App\Services;

use App\Interfaces\PenilaianRepositoryInterface;
use App\Models\KKM;
use Exception;
use Illuminate\Support\Facades\DB;

readonly class PenilaianService
{
    public function __construct(
        private PenilaianRepositoryInterface $penilaianRepository
    ) {}

    /**
     * Memproses dan menyimpan nilai dari form.
     */
    public function processAndSave(array $data): \Illuminate\Database\Eloquent\Model
    {
        if ($this->penilaianRepository->checkDuplicate($data['siswa_id'], $data['mapel_id'], $data['semester_id'])) {
            throw new Exception("Data penilaian untuk mata pelajaran ini pada semester terkait sudah ada.");
        }

        $kalkulasi = $this->calculatePenilaianData($data);

        return DB::transaction(fn() => $this->penilaianRepository->create($kalkulasi));
    }

    public function processAndUpdate(int $id, array $data): bool
    {
        $kalkulasi = $this->calculatePenilaianData($data);

        return DB::transaction(fn() => $this->penilaianRepository->update($id, $kalkulasi));
    }

    /**
     * Engine Kalkulasi Nilai dan Predikat.
     */
    private function calculatePenilaianData(array $data): array
    {
        // Standar Bobot SMP Kurikulum
        $harian = $data['nilai_harian'] ?? 0;
        $tugas = $data['tugas'] ?? 0;
        $quiz = $data['quiz'] ?? 0;
        $uts = $data['uts'] ?? 0;
        $uas = $data['uas'] ?? 0;
        $praktik = $data['praktik'] ?? 0;
        $proyek = $data['proyek'] ?? 0;
        $portofolio = $data['portofolio'] ?? 0;

        $nilaiAkhir = round(
            ($harian * 0.15) +
                ($tugas * 0.15) +
                ($quiz * 0.10) +
                ($uts * 0.25) +
                ($uas * 0.25) +
                ($praktik * 0.10),
            2
        );

        $predikat = match (true) {
            $nilaiAkhir >= 90 => 'A',
            $nilaiAkhir >= 80 => 'B',
            $nilaiAkhir >= 70 => 'C',
            default => 'D',
        };

        // Ambil KKM (Default 75 jika belum didefinisikan untuk tingkat kelas ini)
        $kkm = KKM::where('mapel_id', $data['mapel_id'])->first();
        $batasKkm = $kkm ? $kkm->nilai_kkm : 75;

        $isRemedial = $nilaiAkhir < $batasKkm;

        $data['nilai_akhir'] = $nilaiAkhir;
        $data['predikat'] = $predikat;
        $data['is_remedial'] = $isRemedial;

        // Logika deskripsi otomatis berdasarkan predikat
        if (!isset($data['deskripsi'])) {
            $data['deskripsi'] = "Menunjukkan penguasaan materi dengan predikat {$predikat}.";
        }

        return $data;
    }
}
