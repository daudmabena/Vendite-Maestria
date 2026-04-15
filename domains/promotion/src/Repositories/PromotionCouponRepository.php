<?php

declare(strict_types=1);

namespace Modules\Promotion\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\Promotion\Repositories\Contracts\PromotionCouponRepositoryInterface;
use Modules\Promotion\Models\PromotionCoupon;

/**
 * @extends ShopCoreAbstractShopRepository<PromotionCoupon>
 */
final class PromotionCouponRepository extends ShopCoreAbstractShopRepository implements PromotionCouponRepositoryInterface
{
    public static function modelClass(): string
    {
        return PromotionCoupon::class;
    }
}
