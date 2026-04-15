<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Repositories\Contracts;

use Modules\Fulfillment\Models\ShipmentUnit;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<ShipmentUnit>
 */
interface ShipmentUnitRepositoryInterface extends CrudRepositoryInterface
{
}
