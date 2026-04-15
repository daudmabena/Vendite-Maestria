<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Modules\Catalog\Models\ProductReview;
use Modules\Catalog\Repositories\Contracts\ProductReviewRepositoryInterface;
use Modules\ShopCore\Repositories\AbstractShopRepository;

/**
 * @extends AbstractShopRepository<ProductReview>
 */
final class ProductReviewRepository extends AbstractShopRepository implements ProductReviewRepositoryInterface
{
    public static function modelClass(): string
    {
        return ProductReview::class;
    }
}

