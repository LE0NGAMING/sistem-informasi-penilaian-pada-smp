<?php

namespace App\Http\Requests\Rapor;

use Illuminate\Foundation\Http\FormRequest;

class GenerateRaporRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin_sekolah', 'wali_kelas']);
    }

    public function rules(): array
    {
        return [
            'rombel_id' => ['required', 'integer', 'exists:rombel,id'],
            'semester_id' => ['required', 'integer', 'exists:semester,id'],
            'siswa_id' => ['nullable', 'integer', 'exists:siswa,id'], // Nullable untuk generate 1 kelas secara batch
        ];
    }
}
