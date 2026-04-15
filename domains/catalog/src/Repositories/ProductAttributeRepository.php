<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Modules\Catalog\Models\ProductAttribute;
use Modules\Catalog\Repositories\Contracts\ProductAttributeRepositoryInterface;
use Modules\ShopCore\Repositories\AbstractShopRepository;

/**
 * @extends AbstractShopRepository<ProductAttribute>
 */
final class ProductAttributeRepository extends AbstractShopRepository implements ProductAttributeRepositoryInterface
{
    public static function modelClass(): string
    {
        return ProductAttribute::class;
    }

    public function findByCode(string $code): ?ProductAttribute
    {
        /** @var ProductAttribute|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
