<?php

namespace App\Http\Requests\WaliKelas;

use Illuminate\Foundation\Http\FormRequest;

class StoreNilaiEkskulRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pengecekan otorisasi ditangani di controller atau policy
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajaran,id'],
            'semester'        => ['required', 'in:ganjil,genap'],
            'nilai'           => ['required', 'array'],
            'nilai.*'         => ['array'],
            'nilai.*.*.predikat'   => ['nullable', 'string', 'max:5'],
            'nilai.*.*.keterangan' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
            'semester.required'        => 'Semester wajib dipilih.',
            'nilai.required'           => 'Data nilai tidak boleh kosong.',
        ];
    }
}
