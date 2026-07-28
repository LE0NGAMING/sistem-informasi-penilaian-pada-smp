<?php

namespace App\Repositories;

use App\Interfaces\RaporRepositoryInterface;
use App\Models\Rapor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RaporRepository extends BaseRepository implements RaporRepositoryInterface
{
    public function __construct(Rapor $model)
    {
        parent::__construct($model);
    }

    public function getByRombelAndSemester(int $rombelId, int $semesterId): Collection
    {
        return $this->model->with(['siswa'])
            ->where('rombel_id', $rombelId)
            ->where('semester_id', $semesterId)
            ->orderByDesc('rata_rata')
            ->get();
    }

    public function findBySiswaAndSemester(int $siswaId, int $semesterId): ?Model
    {
        return $this->model->where('siswa_id', $siswaId)
            ->where('semester_id', $semesterId)
            ->first();
    }

    public function updateRankingBulk(array $rankingData): bool
    {
        return DB::transaction(function () use ($rankingData) {
            foreach ($rankingData as $data) {
                $this->model->where('id', $data['id'])->update(['ranking' => $data['ranking']]);
            }
            return true;
        });
    }
}
