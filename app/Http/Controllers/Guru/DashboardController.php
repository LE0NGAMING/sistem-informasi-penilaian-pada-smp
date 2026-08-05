<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKelas = Kelas::count();
        $totalSiswa = Siswa::count();

        return view('guru.dashboard', compact('totalKelas', 'totalSiswa'));
    }
}
