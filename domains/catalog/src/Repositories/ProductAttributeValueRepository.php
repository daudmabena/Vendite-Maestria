<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Modules\Catalog\Models\ProductAttributeValue;
use Modules\Catalog\Repositories\Contracts\ProductAttributeValueRepositoryInterface;
use Modules\ShopCore\Repositories\AbstractShopRepository;

/**
 * @extends AbstractShopRepository<ProductAttributeValue>
 */
final class ProductAttributeValueRepository extends AbstractShopRepository implements ProductAttributeValueRepositoryInterface
{
    public static function modelClass(): string
    {
        return ProductAttributeValue::class;
    }
}
