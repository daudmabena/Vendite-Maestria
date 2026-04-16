<?php

declare(strict_types=1);

namespace Modules\Brand\Repositories\Contracts;

use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;
use Modules\Brand\Models\Touchpoint;
use Illuminate\Support\Collection;

/**
 * @extends CrudRepositoryInterface<Touchpoint>
 */
interface TouchpointRepositoryInterface extends CrudRepositoryInterface
{
    /** All touchpoints for a customer, newest first. */
    public function forCustomer(int $customerId, int $limit = 50): Collection;

    /** Count touchpoints per type for a customer in the last N days. */
    public function countByTypeForCustomer(int $customerId, int $days = 30): Collection;
}
