<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\Fulfillment\Repositories\Contracts\ShipmentUnitRepositoryInterface;
use Modules\Fulfillment\Models\ShipmentUnit;

/**
 * @extends ShopCoreAbstractShopRepository<ShipmentUnit>
 */
final class ShipmentUnitRepository extends ShopCoreAbstractShopRepository implements ShipmentUnitRepositoryInterface
{
    public static function modelClass(): string
    {
        return ShipmentUnit::class;
    }
}
