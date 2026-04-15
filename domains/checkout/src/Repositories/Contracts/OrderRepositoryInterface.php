<?php

declare(strict_types=1);

namespace Modules\Checkout\Repositories\Contracts;

use Modules\Checkout\Models\Order;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Order>
 */
interface OrderRepositoryInterface extends CrudRepositoryInterface
{
    public function findByNumber(string $number): ?Order;

    public function findByToken(string $token): ?Order;
}
