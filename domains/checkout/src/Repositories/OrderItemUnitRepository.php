<?php

declare(strict_types=1);

namespace Modules\Checkout\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\Checkout\Repositories\Contracts\OrderItemUnitRepositoryInterface;
use Modules\Checkout\Models\OrderItemUnit;

/**
 * @extends AbstractShopRepository<OrderItemUnit>
 */
final class OrderItemUnitRepository extends AbstractShopRepository implements OrderItemUnitRepositoryInterface
{
    public static function modelClass(): string
    {
        return OrderItemUnit::class;
    }
}
