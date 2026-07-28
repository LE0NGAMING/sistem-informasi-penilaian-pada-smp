<?php

namespace App\Http\Requests\Penilaian;

use Illuminate\Foundation\Http\FormRequest;

class StorePenilaianRequest extends FormRequest
{
    public function authorize(): bool
    {
        // RBAC sudah diatasi oleh Middleware/Policy, 
        // tapi Form Request memastikan request datang dari User yang Valid
        return auth()->check() && auth()->user()->isGuru();
    }

    public function rules(): array
    {
        return [
            'siswa_id' => ['required', 'integer', 'exists:siswa,id'],
            'mapel_id' => ['required', 'integer', 'exists:mapel,id'],
            'semester_id' => ['required', 'integer', 'exists:semester,id'],
            'rombel_id' => ['required', 'integer', 'exists:rombel,id'],
            'guru_id' => ['required', 'integer', 'exists:guru,id'],

            // Komponen nilai harus numerik (0-100)
            'nilai_harian' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tugas' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'quiz' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'uts' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'uas' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'praktik' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'proyek' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'portofolio' => ['nullable', 'numeric', 'min:0', 'max:100'],

            'deskripsi' => ['nullable', 'string', 'max:500'], // Mencegah payload String yang terlalu besar (OWASP)
        ];
    }

    public function messages(): array
    {
        return [
            'siswa_id.exists' => 'Data Siswa tidak valid.',
            'nilai_harian.max' => 'Nilai harian tidak boleh lebih dari 100.',
            'uts.max' => 'Nilai UTS tidak boleh lebih dari 100.',
        ];
    }
}
