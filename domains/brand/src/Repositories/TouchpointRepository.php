<?php

declare(strict_types=1);

namespace Modules\Brand\Repositories;

use Modules\Brand\Models\Touchpoint;
use Modules\Brand\Repositories\Contracts\TouchpointRepositoryInterface;
use Modules\ShopCore\Repositories\AbstractShopRepository;
use Illuminate\Support\Collection;

/**
 * @extends AbstractShopRepository<Touchpoint>
 */
final class TouchpointRepository extends AbstractShopRepository implements TouchpointRepositoryInterface
{
    public static function modelClass(): string
    {
        return Touchpoint::class;
    }

    public function forCustomer(int $customerId, int $limit = 50): Collection
    {
        return $this->query()
            ->where('customer_id', $customerId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function countByTypeForCustomer(int $customerId, int $days = 30): Collection
    {
        return $this->query()
            ->where('customer_id', $customerId)
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();
    }
}
