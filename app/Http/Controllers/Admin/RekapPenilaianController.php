<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\SemesterEnum;
use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\Rombel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RekapPenilaianController extends Controller
{
    /**
     * Menampilkan rekapitulasi penilaian siswa untuk Admin (Full Access).
     */
    public function index(Request $request): View
    {
        // Admin dapat melihat seluruh rombel dan mapel tanpa batasan pengampu
        $rombelList = Rombel::orderBy('nama_rombel')->get();
        $mapelList = Mapel::orderBy('nama_mapel')->get();

        $rombelId = $request->query('rombel_id');
        $mapelId = $request->query('mapel_id');
        $semester = $request->query('semester_id');

        $siswaList = collect();

        // Jika semua filter terisi, ambil data siswa beserta relasi penilaiannya
        if ($rombelId && $mapelId && $semester) {
            $siswaList = Siswa::query()
                ->where('rombel_id', $rombelId)
                ->with(['penilaian' => function ($query) use ($mapelId, $semester) {
                    $query->where('mapel_id', $mapelId)
                        ->where('semester', $semester);
                }])
                ->orderBy('nama_lengkap')
                ->get();
        }

        return view('admin.rekap.index', [
            'rombelList'   => $rombelList,
            'mapelList'    => $mapelList,
            'semesterList' => SemesterEnum::cases(),
            'siswaList'    => $siswaList,
            'rombelId'     => $rombelId,
            'mapelId'      => $mapelId,
            'semesterId'   => $semester,
        ]);
    }
}
