<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlotSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'siswa_ids'   => ['required', 'array', 'min:1'],
            'siswa_ids.*' => ['required', 'exists:siswa,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'siswa_ids.required' => 'Pilih minimal satu siswa untuk di-plotting.',
            'siswa_ids.*.exists' => 'Data siswa tidak valid.',
        ];
    }
}
