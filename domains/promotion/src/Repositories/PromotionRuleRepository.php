<?php

declare(strict_types=1);

namespace Modules\Promotion\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\Promotion\Repositories\Contracts\PromotionRuleRepositoryInterface;
use Modules\Promotion\Models\PromotionRule;

/**
 * @extends ShopCoreAbstractShopRepository<PromotionRule>
 */
final class PromotionRuleRepository extends ShopCoreAbstractShopRepository implements PromotionRuleRepositoryInterface
{
    public static function modelClass(): string
    {
        return PromotionRule::class;
    }
}
