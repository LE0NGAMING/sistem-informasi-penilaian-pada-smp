<?php

namespace App\Http\Controllers;

use App\Http\Requests\Penilaian\StorePenilaianRequest;
use App\Interfaces\PenilaianRepositoryInterface;
use App\Services\PenilaianService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function __construct(
        private readonly PenilaianService $penilaianService,
        private readonly PenilaianRepositoryInterface $penilaianRepository
    ) {}

    public function index(Request $request): View
    {
        // Pengecekan otorisasi menggunakan Gate/Policy di route middleware
        $penilaian = $this->penilaianRepository->paginate(15, ['*'], ['siswa', 'mapel', 'guru', 'rombel']);
        return view('penilaian.index', compact('penilaian'));
    }

    public function create(): View
    {
        return view('penilaian.create');
        // Catatan: Variabel master data (siswa, mapel, dll) idealnya di-inject via View Composer atau diambil via AJAX
    }

    public function store(StorePenilaianRequest $request): RedirectResponse
    {
        try {
            $this->penilaianService->processAndSave($request->validated());

            return redirect()->route('penilaian.index')
                ->with('success', 'Data penilaian berhasil disimpan.');
        } catch (Exception $e) {
            Log::error('Gagal menyimpan penilaian: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage()); // Error message custom dari Service
        }
    }

    public function edit(int $id): View
    {
        $penilaian = $this->penilaianRepository->findById($id);

        // Authorization check menggunakan Policy (Laravel 11/12 style)
        \Illuminate\Support\Facades\Gate::authorize('update', $penilaian);

        return view('penilaian.edit', compact('penilaian'));
    }

    public function update(StorePenilaianRequest $request, int $id): RedirectResponse
    {
        $penilaian = $this->penilaianRepository->findById($id);
        \Illuminate\Support\Facades\Gate::authorize('update', $penilaian);

        try {
            $this->penilaianService->processAndUpdate($id, $request->validated());

            return redirect()->route('penilaian.index')
                ->with('success', 'Data penilaian berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $penilaian = $this->penilaianRepository->findById($id);
        \Illuminate\Support\Facades\Gate::authorize('delete', $penilaian);

        $this->penilaianRepository->delete($id);

        return redirect()->route('penilaian.index')
            ->with('success', 'Data penilaian berhasil dihapus.');
    }
}
