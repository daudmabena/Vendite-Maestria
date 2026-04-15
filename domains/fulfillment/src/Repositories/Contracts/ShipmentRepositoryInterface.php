<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Repositories\Contracts;

use Modules\Fulfillment\Models\Shipment;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Shipment>
 */
interface ShipmentRepositoryInterface extends CrudRepositoryInterface
{
}
