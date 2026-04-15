<?php

declare(strict_types=1);

namespace Modules\Promotion\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\Promotion\Repositories\Contracts\PromotionActionRepositoryInterface;
use Modules\Promotion\Models\PromotionAction;

/**
 * @extends ShopCoreAbstractShopRepository<PromotionAction>
 */
final class PromotionActionRepository extends ShopCoreAbstractShopRepository implements PromotionActionRepositoryInterface
{
    public static function modelClass(): string
    {
        return PromotionAction::class;
    }
}
