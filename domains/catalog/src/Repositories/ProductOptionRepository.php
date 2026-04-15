<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Modules\Catalog\Models\ProductOption;
use Modules\Catalog\Repositories\Contracts\ProductOptionRepositoryInterface;
use Modules\ShopCore\Repositories\AbstractShopRepository;

/**
 * @extends AbstractShopRepository<ProductOption>
 */
final class ProductOptionRepository extends AbstractShopRepository implements ProductOptionRepositoryInterface
{
    public static function modelClass(): string
    {
        return ProductOption::class;
    }

    public function findByCode(string $code): ?ProductOption
    {
        /** @var ProductOption|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
