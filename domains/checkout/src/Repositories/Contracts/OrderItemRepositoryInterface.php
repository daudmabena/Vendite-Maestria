<?php

declare(strict_types=1);

namespace Modules\Checkout\Repositories\Contracts;

use Modules\Checkout\Models\OrderItem;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<OrderItem>
 */
interface OrderItemRepositoryInterface extends CrudRepositoryInterface
{
}
