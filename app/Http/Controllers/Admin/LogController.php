<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Illuminate\View\View;

class LogController extends Controller
{
    /**
     * Menampilkan daftar log aktivitas dan pengguna yang sedang online.
     */
    public function index(): View
    {
        // Mengambil log aktivitas terbaru dengan relasi 'causer' (menggunakan pagination)
        $logs = Activity::with('causer')->latest()->paginate(20);

        // Mengambil daftar pengguna yang sedang aktif (online dalam 5 menit terakhir)
        $onlineUsers = User::whereIn(
            'id',
            DB::table('sessions')
                ->where('last_activity', '>=', now()->subMinutes(5)->getTimestamp())
                ->whereNotNull('user_id')
                ->pluck('user_id')
        )->get();

        return view('admin.logs.index', compact('logs', 'onlineUsers'));
    }
}
