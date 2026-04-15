<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Modules\Catalog\Repositories\Contracts\ProductRepositoryInterface;
use Modules\Catalog\Models\Product;
use Modules\ShopCore\Repositories\AbstractShopRepository;

/**
 * @extends AbstractShopRepository<Product>
 */
final class ProductRepository extends AbstractShopRepository implements ProductRepositoryInterface
{
    public static function modelClass(): string
    {
        return Product::class;
    }

    public function findByCode(string $code): ?Product
    {
        /** @var Product|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
