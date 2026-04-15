<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Modules\Catalog\Models\ProductImage;
use Modules\Catalog\Repositories\Contracts\ProductImageRepositoryInterface;
use Modules\ShopCore\Repositories\AbstractShopRepository;

/**
 * @extends AbstractShopRepository<ProductImage>
 */
final class ProductImageRepository extends AbstractShopRepository implements ProductImageRepositoryInterface
{
    public static function modelClass(): string
    {
        return ProductImage::class;
    }
}
