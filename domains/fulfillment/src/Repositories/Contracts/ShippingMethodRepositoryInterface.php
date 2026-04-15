<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Repositories\Contracts;

use Modules\Fulfillment\Models\ShippingMethod;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<ShippingMethod>
 */
interface ShippingMethodRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?ShippingMethod;
}
