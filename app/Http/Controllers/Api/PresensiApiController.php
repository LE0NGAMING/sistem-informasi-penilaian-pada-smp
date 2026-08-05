<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiApiController extends Controller
{
    public function tapMachine(Request $request)
    {
        // Validation payload dari mesin (misal mesin mengirim uid kartu RFID / NISN)
        $request->validate([
            'uid' => 'required|string', // atau 'nisn'
        ]);

        $siswa = Siswa::where('card_uid', $request->uid)
            ->orWhere('nisn', $request->uid)
            ->first();

        if (!$siswa) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Siswa tidak ditemukan!'
            ], 404);
        }

        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();

        // Cari atau buat record presensi hari ini
        $presensi = Presensi::firstOrNew([
            'siswa_id' => $siswa->id,
            'tanggal'  => $today,
        ]);

        if (!$presensi->exists) {
            // Tap pertama: Jam Masuk
            $presensi->kelas_id  = $siswa->kelas_id;
            $presensi->jam_masuk = $now;
            $presensi->status    = 'hadir';
            $presensi->metode    = 'mesin_rfid';
            $presensi->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Presensi Masuk Berhasil',
                'data'    => [
                    'nama'  => $siswa->nama_lengkap,
                    'waktu' => $now
                ]
            ], 200);
        } else {
            // Tap kedua di hari yang sama: Jam Pulang
            $presensi->jam_pulang = $now;
            $presensi->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Presensi Pulang Berhasil',
                'data'    => [
                    'nama'  => $siswa->nama_lengkap,
                    'waktu' => $now
                ]
            ], 200);
        }
    }
}
