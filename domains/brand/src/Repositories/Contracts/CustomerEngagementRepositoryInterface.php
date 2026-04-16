<?php

declare(strict_types=1);

namespace Modules\Brand\Repositories\Contracts;

use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;
use Modules\Brand\Models\CustomerEngagement;
use Illuminate\Support\Collection;

/**
 * @extends CrudRepositoryInterface<CustomerEngagement>
 */
interface CustomerEngagementRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCustomer(int $customerId): ?CustomerEngagement;

    public function findOrCreateForCustomer(int $customerId): CustomerEngagement;

    /** Customers whose last_seen_at is older than $days. */
    public function findLapsing(int $days = 30): Collection;

    /** Distribution of customers per trust_tier. */
    public function tierDistribution(): Collection;
}
