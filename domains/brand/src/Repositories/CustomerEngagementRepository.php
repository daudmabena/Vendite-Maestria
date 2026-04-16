<?php

declare(strict_types=1);

namespace Modules\Brand\Repositories;

use Modules\Brand\Models\CustomerEngagement;
use Modules\Brand\Repositories\Contracts\CustomerEngagementRepositoryInterface;
use Modules\ShopCore\Repositories\AbstractShopRepository;
use Illuminate\Support\Collection;

/**
 * @extends AbstractShopRepository<CustomerEngagement>
 */
final class CustomerEngagementRepository extends AbstractShopRepository implements CustomerEngagementRepositoryInterface
{
    public static function modelClass(): string
    {
        return CustomerEngagement::class;
    }

    public function findByCustomer(int $customerId): ?CustomerEngagement
    {
        return $this->query()->where('customer_id', $customerId)->first();
    }

    public function findOrCreateForCustomer(int $customerId): CustomerEngagement
    {
        return $this->query()->firstOrCreate(
            ['customer_id' => $customerId],
            [
                'familiarity_score' => 0,
                'total_touchpoints' => 0,
                'trust_tier'        => 'cold',
            ],
        );
    }

    public function findLapsing(int $days = 30): Collection
    {
        return $this->query()
            ->where('last_seen_at', '<=', now()->subDays($days))
            ->orWhereNull('last_seen_at')
            ->get();
    }

    public function tierDistribution(): Collection
    {
        return $this->query()
            ->selectRaw('trust_tier, COUNT(*) as count')
            ->groupBy('trust_tier')
            ->get();
    }
}
