<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenilaianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mapel' => [
                'kode' => $this->mapel->kode,
                'nama' => $this->mapel->nama_mapel,
            ],
            'guru' => $this->guru->nama_lengkap,
            'semester' => ucfirst($this->semester->semester) . ' ' . $this->semester->tahunAjaran->tahun,
            'komponen_nilai' => [
                'harian' => (float) $this->nilai_harian,
                'tugas' => (float) $this->tugas,
                'uts' => (float) $this->uts,
                'uas' => (float) $this->uas,
                'praktik' => (float) $this->praktik,
            ],
            'hasil_akhir' => [
                'nilai' => (float) $this->nilai_akhir,
                'predikat' => $this->predikat,
                'is_remedial' => (bool) $this->is_remedial,
                'deskripsi' => $this->deskripsi,
            ],
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
