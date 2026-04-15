<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Modules\Catalog\Repositories\Contracts\ProductVariantRepositoryInterface;
use Modules\Catalog\Models\ProductVariant;
use Modules\ShopCore\Repositories\AbstractShopRepository;

/**
 * @extends AbstractShopRepository<ProductVariant>
 */
final class ProductVariantRepository extends AbstractShopRepository implements ProductVariantRepositoryInterface
{
    public static function modelClass(): string
    {
        return ProductVariant::class;
    }
}
