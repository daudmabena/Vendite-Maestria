<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Generic resource-style CRUD contract for Eloquent models.
 *
 * @template TModel of Model
 */
interface CrudRepositoryInterface
{
    /**
     * @return class-string<TModel>
     */
    public static function modelClass(): string;

    public function query(): Builder;

    /**
     * @param  array<int, string>  $columns
     * @return Collection<int, TModel>
     */
    public function all(array $columns = ['*']): Collection;

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * @param  array<int, string>  $columns
     * @return TModel|null
     */
    public function find(int $id, array $columns = ['*']): ?Model;

    /**
     * @param  array<int, string>  $columns
     * @return TModel
     */
    public function findOrFail(int $id, array $columns = ['*']): Model;

    /**
     * @param  array<string, mixed>  $criteria
     * @return TModel|null
     */
    public function findOneBy(array $criteria): ?Model;

    /**
     * @param  array<string, mixed>  $data
     * @return TModel
     */
    public function create(array $data): Model;

    /**
     * @param  array<string, mixed>  $data
     * @return TModel
     */
    public function update(Model $model, array $data): Model;

    public function delete(Model $model): bool;
}
