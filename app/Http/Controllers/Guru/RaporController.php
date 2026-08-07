<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\Semester;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    /**
     * Cetak Rapor Per Siswa
     */
    public function cetakSiswa(Request $request, Siswa $siswa)
    {
        $semesterId = $request->get('semester');

        $siswa->load([
            'rombel.waliKelas',
            'penilaian' => function ($query) use ($semesterId) {
                if ($semesterId) {
                    $query->where('semester', $semesterId);
                }
                $query->with('mapel');
            }
        ]);

        $pdf = Pdf::loadView('guru.rapor.pdf', [
            'siswa' => $siswa,
            'penilaianList' => $siswa->penilaian,
            'semester' => $semesterId,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        $filename = "Rapor_{$siswa->nisn}_{$siswa->nama_lengkap}.pdf";

        return $pdf->stream($filename);
    }

    /**
     * Cetak Rapor Massal 1 Rombel (Bulk Print)
     */
    public function cetakRombel(Request $request, Rombel $rombel)
    {
        $semesterId = $request->get('semester');

        $siswaList = Siswa::where('rombel_id', $rombel->id)
            ->with([
                'rombel.waliKelas',
                'penilaian' => function ($query) use ($semesterId) {
                    if ($semesterId) {
                        $query->where('semester', $semesterId);
                    }
                    $query->with('mapel');
                }
            ])
            ->get();

        $pdf = Pdf::loadView('guru.rapor.pdf', [
            'rombel' => $rombel,
            'siswaList' => $siswaList,
            'semester' => $semesterId,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("Rapor_Rombel_{$rombel->nama_rombel}.pdf");
    }
}
