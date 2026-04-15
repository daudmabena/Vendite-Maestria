<?php

declare(strict_types=1);

namespace Modules\Checkout\Repositories\Contracts;

use Modules\Checkout\Models\OrderItemUnit;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<OrderItemUnit>
 */
interface OrderItemUnitRepositoryInterface extends CrudRepositoryInterface
{
}
