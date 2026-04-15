<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\Fulfillment\Repositories\Contracts\ShipmentRepositoryInterface;
use Modules\Fulfillment\Models\Shipment;

/**
 * @extends ShopCoreAbstractShopRepository<Shipment>
 */
final class ShipmentRepository extends ShopCoreAbstractShopRepository implements ShipmentRepositoryInterface
{
    public static function modelClass(): string
    {
        return Shipment::class;
    }
}
