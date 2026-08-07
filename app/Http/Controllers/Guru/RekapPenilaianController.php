<?php

namespace App\Http\Controllers\Guru;

use App\Enums\SemesterEnum;
use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\Rombel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class RekapPenilaianController extends Controller
{
    public function index(Request $request): View
    {
        $rombelList = Rombel::orderBy('nama_rombel')->get();
        $mapelList = Mapel::orderBy('nama_mapel')->get();
        $semesterList = SemesterEnum::cases();

        $rombelId = $request->query('rombel_id');
        $mapelId = $request->query('mapel_id');
        $semesterId = $request->query('semester_id');

        if ($rombelId && $mapelId && $semesterId) {
            $siswaList = Siswa::where('rombel_id', $rombelId)
                ->with(['penilaian' => fn($q) => $q->where('mapel_id', $mapelId)->where('semester', $semesterId)])
                ->orderBy('nama_lengkap')
                ->paginate(15)
                ->withQueryString();
        } else {
            // Mencegah error MethodNotAllowed / BadMethodCall di Blade jika filter belum dipilih
            $siswaList = new LengthAwarePaginator([], 0, 15);
        }

        return view('guru.rekap.index', compact(
            'rombelList',
            'mapelList',
            'semesterList',
            'rombelId',
            'mapelId',
            'semesterId',
            'siswaList'
        ));
    }

    public function cetak(Request $request): View
    {
        $validated = $request->validate([
            'rombel_id'   => ['required', 'exists:rombel,id'],
            'mapel_id'    => ['required', 'exists:mapel,id'],
            'semester_id' => ['required'],
        ]);

        $rombel = Rombel::findOrFail($validated['rombel_id']);
        $mapel = Mapel::findOrFail($validated['mapel_id']);
        $semester = SemesterEnum::tryFrom($validated['semester_id']);

        // Halaman cetak tetap menggunakan get() agar semua siswa tercetak tanpa terpotong halaman
        $siswaList = Siswa::where('rombel_id', $rombel->id)
            ->with(['penilaian' => fn($q) => $q->where('mapel_id', $mapel->id)->where('semester', $validated['semester_id'])])
            ->orderBy('nama_lengkap')
            ->get();

        return view('guru.rekap.cetak', compact('rombel', 'mapel', 'semester', 'siswaList'));
    }
}
