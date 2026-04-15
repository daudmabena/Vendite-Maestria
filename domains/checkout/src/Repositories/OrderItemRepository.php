<?php

declare(strict_types=1);

namespace Modules\Checkout\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\Checkout\Repositories\Contracts\OrderItemRepositoryInterface;
use Modules\Checkout\Models\OrderItem;

/**
 * @extends AbstractShopRepository<OrderItem>
 */
final class OrderItemRepository extends AbstractShopRepository implements OrderItemRepositoryInterface
{
    public static function modelClass(): string
    {
        return OrderItem::class;
    }
}
