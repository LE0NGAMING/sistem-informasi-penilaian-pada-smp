<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Rombel;
use Illuminate\Foundation\Http\FormRequest;

class StorePresensiRequest extends FormRequest
{
    /**
     * Menentukan apakah user diizinkan melakukan request ini.
     */
    public function authorize(): bool
    {
        $guru = $this->user()?->guru;

        if (!$guru) {
            return false;
        }

        // Cek apakah guru ini terdaftar sebagai wali kelas di sistem
        return Rombel::where('wali_kelas_id', $guru->id)->exists();
    }

    /**
     * Aturan validasi input.
     */
    public function rules(): array
    {
        return [
            'tanggal'             => ['required', 'date'],
            'presensi'            => ['required', 'array'],
            // Disesuaikan agar menerima huruf kecil (sesuai value radio button di Blade)
            'presensi.*.status'   => ['required', 'in:hadir,sakit,izin,alpa,Hadir,Sakit,Izin,Alfa'],
            'presensi.*.catatan'  => ['nullable', 'string', 'max:255'],
            'presensi.*.keterangan' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Kustomisasi pesan error.
     */
    public function messages(): array
    {
        return [
            'presensi.*.status.in' => 'Status presensi tidak valid. Pilih Hadir, Sakit, Izin, atau Alpa.',
        ];
    }
}
