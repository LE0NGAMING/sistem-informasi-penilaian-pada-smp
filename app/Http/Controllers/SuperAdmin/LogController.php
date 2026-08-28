<?php

namespace App\Http\Controllers\SuperAdmin;

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
        // Ambil log biasa
        $logs = Activity::latest()->paginate(20); // hapus with('causer') dulu buat ngetes

        // Fitur "online users" dimatikan sementara karena pake session file
        // Kalau pake file, data session ada di storage/framework/sessions bukan di DB
        $onlineUsers = collect();

        return view('superadmin.logs.index', compact('logs', 'onlineUsers'));
    }
}
