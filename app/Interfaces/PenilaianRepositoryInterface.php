<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface PenilaianRepositoryInterface extends BaseRepositoryInterface
{
    public function getBySiswaAndSemester(int $siswaId, int $semesterId): Collection;
    public function checkDuplicate(int $siswaId, int $mapelId, int $semesterId): bool;
}
