<?php

namespace Tests\Unit;

use App\Interfaces\PenilaianRepositoryInterface;
use App\Models\KKM;
use App\Services\PenilaianService;
use Mockery;
use Tests\TestCase;

class PenilaianServiceTest extends TestCase
{
    public function test_kalkulasi_nilai_akhir_dan_predikat_berhasil_sesuai_bobot(): void
    {
        $repositoryMock = Mockery::mock(PenilaianRepositoryInterface::class);
        $service = new PenilaianService($repositoryMock);

        $inputData = [
            'siswa_id' => 1,
            'mapel_id' => 1,
            'semester_id' => 1,
            'nilai_harian' => 80, // 80 * 0.15 = 12
            'tugas' => 90,        // 90 * 0.15 = 13.5
            'quiz' => 85,         // 85 * 0.10 = 8.5
            'uts' => 88,          // 88 * 0.25 = 22
            'uas' => 92,          // 92 * 0.25 = 23
            'praktik' => 90,      // 90 * 0.10 = 9
            // Total Nilai Akhir Expected = 12 + 13.5 + 8.5 + 22 + 23 + 9 = 88.00 (Predikat B)
        ];

        // Access private method using Reflection for direct unit testing
        $reflection = new \ReflectionClass(PenilaianService::class);
        $method = $reflection->getMethod('calculatePenilaianData');
        $method->setAccessible(true);

        $result = $method->invoke($service, $inputData);

        $this->assertEquals(88.00, $result['nilai_akhir']);
        $this->assertEquals('B', $result['predikat']);
        $this->assertFalse($result['is_remedial']); // Nilai 88 >= KKM (75)
    }
}
