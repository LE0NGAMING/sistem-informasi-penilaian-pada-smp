<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }
    public function findById(int $id, array $columns = ['*']): ?Model
    {
        return $this->model->select($columns)->findOrFail($id);
    }
    public function create(array $payload): Model
    {
        return $this->model->create($payload);
    }
    public function update(int $id, array $payload): bool
    {
        $model = $this->findById($id);
        return $model->update($payload);
    }
    public function delete(int $id): bool
    {
        $model = $this->findById($id);
        return $model->delete();
    }
    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = [])
    {
        return $this->model->with($relations)->select($columns)->paginate($perPage);
    }
}
