<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Enums\SemesterEnum; // Import Enum sesuai project Anda
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data rombel untuk dropdown pilihan rombel
        $rombelList = Rombel::with('kelas')->latest()->get();

        // Ambil parameter filter rombel dan semester
        $rombelId = $request->get('rombel_id');
        $semesterId = $request->get('semester_id');

        // Helper untuk daftar semester
        $semesterList = [
            new class('Ganjil', 'Semester Ganjil') {
                public function __construct(public string $value, private string $name) {}
                public function label()
                {
                    return $this->name;
                }
            },
            new class('Genap', 'Semester Genap') {
                public function __construct(public string $value, private string $name) {}
                public function label()
                {
                    return $this->name;
                }
            }
        ];

        // Ambil data siswa jika rombel sudah dipilih
        $siswaList = [];
        if ($rombelId) {
            // Sesuaikan jika relasi di model Rombel bernama 'siswa' atau 'siswas'
            // Atau jika menggunakan foreign key langsung: Siswa::where('rombel_id', $rombelId)->get();
            $rombel = Rombel::with('siswa')->find($rombelId);
            $siswaList = $rombel ? $rombel->siswa : [];
        }

        return view('guru.rapor.index', compact('rombelList', 'rombelId', 'semesterList', 'semesterId', 'siswaList'));
    }

    /**
     * Cetak Rapor Per Siswa (Individu)
     */
    public function cetakSiswa(Request $request, Siswa $siswa)
    {
        // Tangkap parameter dari URL (name input di form adalah semester_id)
        $semesterFilter = $request->query('semester_id');

        // Eager load relasi untuk optimasi (Mencegah N+1 Query)
        $siswa->load([
            'rombel.waliKelas',
            'penilaian' => function ($query) use ($semesterFilter) {
                if ($semesterFilter) {
                    // FIX: Nama kolom disesuaikan dengan gambar tabel (semester)
                    $query->where('semester', $semesterFilter);
                }
                $query->with('mapel');
            }
        ]);

        // Normalisasi Data: Bungkus 1 siswa ke dalam Collection
        // Agar pdf.blade.php hanya butuh 1 logic perulangan untuk cetak individu maupun massal
        $siswaList = collect([$siswa]);

        $pdf = Pdf::loadView('guru.rapor.pdf', [
            'siswaList'    => $siswaList,
            'semester'     => $semesterFilter,
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
        $semesterFilter = $request->query('semester_id');

        // Gunakan relasi langsung dari model Rombel
        $siswaList = $rombel->siswa()
            ->with([
                'rombel.waliKelas',
                'penilaian' => function ($query) use ($semesterFilter) {
                    if ($semesterFilter) {
                        // FIX: Nama kolom disesuaikan dengan gambar tabel (semester)
                        $query->where('semester', $semesterFilter);
                    }
                    $query->with('mapel');
                }
            ])
            ->orderBy('nama_lengkap', 'asc') // Urutkan nama siswa secara alfabetis
            ->get();

        $pdf = Pdf::loadView('guru.rapor.pdf', [
            'siswaList'    => $siswaList,
            'semester'     => $semesterFilter,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        $filename = "Rapor_Rombel_{$rombel->nama_rombel}.pdf";

        return $pdf->stream($filename);
    }
}
