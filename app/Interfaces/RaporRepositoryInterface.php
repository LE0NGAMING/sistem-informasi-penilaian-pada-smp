<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RaporRepositoryInterface extends BaseRepositoryInterface
{
    public function getByRombelAndSemester(int $rombelId, int $semesterId): Collection;
    public function findBySiswaAndSemester(int $siswaId, int $semesterId): ?Model;
    public function updateRankingBulk(array $rankingData): bool;
}
