<?php

declare(strict_types=1);

namespace Modules\Checkout\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\Checkout\Repositories\Contracts\OrderRepositoryInterface;
use Modules\Checkout\Models\Order;

/**
 * @extends AbstractShopRepository<Order>
 */
final class OrderRepository extends AbstractShopRepository implements OrderRepositoryInterface
{
    public static function modelClass(): string
    {
        return Order::class;
    }

    public function findByNumber(string $number): ?Order
    {
        return $this->query()->where('number', $number)->first();
    }

    public function findByToken(string $token): ?Order
    {
        return $this->query()->where('token_value', $token)->first();
    }
}
