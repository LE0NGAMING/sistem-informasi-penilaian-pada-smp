<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PenilaianApiController;
use App\Http\Controllers\Api\PresensiApiController;
use Illuminate\Support\Facades\Route;

// Public Endpoints
Route::post('/login', [AuthController::class, 'login']);

// Protected Endpoints (Requires API Token via Sanctum)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Feature API
    Route::get('/siswa/penilaian', [PenilaianApiController::class, 'getNilaiSiswa']);

    Route::post('/presensi/tap', [PresensiApiController::class, 'tapMachine']);
});
