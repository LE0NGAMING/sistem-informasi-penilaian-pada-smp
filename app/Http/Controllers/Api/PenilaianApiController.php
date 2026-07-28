<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PenilaianResource;
use App\Interfaces\PenilaianRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PenilaianApiController extends Controller
{
    public function __construct(
        private readonly PenilaianRepositoryInterface $penilaianRepository
    ) {}

    public function getNilaiSiswa(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $user = $request->user();

        // Security Check: Hanya siswa itu sendiri yang bisa melihat nilainya via endpoint ini
        if ($user->role !== 'siswa' || ! $user->siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Hanya entitas siswa yang diizinkan.'
            ], 403);
        }

        $semesterId = $request->query('semester_id');

        if (! $semesterId) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter semester_id wajib disertakan.'
            ], 400);
        }

        $penilaian = $this->penilaianRepository->getBySiswaAndSemester($user->siswa->id, (int) $semesterId);

        return PenilaianResource::collection($penilaian)->additional([
            'success' => true,
            'message' => 'Data penilaian berhasil diambil.'
        ]);
    }
}
