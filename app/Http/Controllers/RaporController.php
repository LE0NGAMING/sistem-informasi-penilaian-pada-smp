<?php

namespace App\Http\Controllers;

use App\Interfaces\PenilaianRepositoryInterface;
use App\Interfaces\RaporRepositoryInterface;
use App\Models\Siswa;
use App\Services\RaporService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RaporController extends Controller
{
    public function __construct(
        private readonly RaporService $raporService,
        private readonly RaporRepositoryInterface $raporRepository,
        private readonly PenilaianRepositoryInterface $penilaianRepository
    ) {}

    /**
     * Menampilkan Preview dan Generate Rapor PDF
     */
    public function printPdf(int $siswaId, int $semesterId): Response
    {
        $siswa = Siswa::with(['rombel'])->findOrFail($siswaId);

        // Ensure Rapor calculations are freshly updated
        $rapor = $this->raporService->generateSiswaRapor($siswaId, $siswa->rombel_id, $semesterId);
        $penilaian = $this->penilaianRepository->getBySiswaAndSemester($siswaId, $semesterId);

        // Generate QR Code Signature Base64 for Dompdf inline rendering
        $qrData = route('rapor.verify', ['uuid' => $rapor->qr_code_signature]);

        // Generate QR code SVG / Base64 string safely
        $qrCodeImage = base64_encode(
            QrCode::format('png')
                ->size(120)
                ->errorCorrection('H')
                ->generate($qrData)
        );

        $pdf = Pdf::loadView('rapor.pdf', compact('siswa', 'rapor', 'penilaian', 'qrCodeImage'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream("Rapor_{$siswa->nisn}_{$siswa->nama_lengkap}.pdf");
    }

    /**
     * Endpoint Publik untuk Verifikasi Autentisitas QR Code Rapor
     */
    public function verifyQrCode(string $uuid)
    {
        $rapor = $this->raporRepository->findByUuid($uuid); // Add method in repo if needed

        if (!$rapor) {
            return view('rapor.verify_failed');
        }

        return view('rapor.verify_success', compact('rapor'));
    }
}
