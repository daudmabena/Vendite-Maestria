<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories;

use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @template TModel of Model
 * @implements CrudRepositoryInterface<TModel>
 */
abstract class AbstractShopRepository implements CrudRepositoryInterface
{
    public function query(): Builder
    {
        return static::modelClass()::query();
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->query()->get($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage, $columns);
    }

    public function find(int $id, array $columns = ['*']): ?Model
    {
        return $this->query()->find($id, $columns);
    }

    public function findOrFail(int $id, array $columns = ['*']): Model
    {
        return $this->query()->findOrFail($id, $columns);
    }

    public function findOneBy(array $criteria): ?Model
    {
        return $this->query()->where($criteria)->first();
    }

    public function create(array $data): Model
    {
        /** @var TModel $model */
        $model = $this->query()->getModel()->newInstance($data);
        $model->save();

        return $model;
    }

    public function update(Model $model, array $data): Model
    {
        $model->fill($data);
        $model->save();

        return $model;
    }

    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }
}
