<?php

namespace App\Repositories;

use App\Interfaces\PenilaianRepositoryInterface;
use App\Models\Penilaian;
use Illuminate\Database\Eloquent\Collection;

class PenilaianRepository extends BaseRepository implements PenilaianRepositoryInterface
{
    public function __construct(Penilaian $model)
    {
        parent::__construct($model);
    }

    public function getBySiswaAndSemester(int $siswaId, int $semesterId): Collection
    {
        return $this->model->with(['mapel', 'guru'])
            ->where('siswa_id', $siswaId)
            ->where('semester_id', $semesterId)
            ->get();
    }

    public function checkDuplicate(int $siswaId, int $mapelId, int $semesterId): bool
    {
        return $this->model->where('siswa_id', $siswaId)
            ->where('mapel_id', $mapelId)
            ->where('semester_id', $semesterId)
            ->exists();
    }
}
