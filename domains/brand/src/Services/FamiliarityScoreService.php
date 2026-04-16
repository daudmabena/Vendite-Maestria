<?php

declare(strict_types=1);

namespace Modules\Brand\Services;

use Modules\Brand\Models\CustomerEngagement;
use Modules\Brand\Repositories\Contracts\CustomerEngagementRepositoryInterface;
use Modules\Brand\Repositories\Contracts\TouchpointRepositoryInterface;

final class FamiliarityScoreService
{
    /**
     * Tier thresholds (inclusive lower bound).
     *
     * @var array<string, int>
     */
    private const TIERS = [
        'loyal'    => 76,
        'familiar' => 51,
        'warming'  => 21,
        'cold'     => 0,
    ];

    public function __construct(
        private readonly CustomerEngagementRepositoryInterface $engagements,
        private readonly TouchpointRepositoryInterface $touchpoints,
    ) {}

    public function recalculate(int $customerId): CustomerEngagement
    {
        $engagement = $this->engagements->findOrCreateForCustomer($customerId);

        $score = $this->computeScore($customerId, $engagement);
        $tier  = $this->resolveTier($score);
        $total = $this->touchpoints->query()
            ->where('customer_id', $customerId)
            ->count();

        $this->engagements->update($engagement, [
            'familiarity_score' => $score,
            'trust_tier'        => $tier,
            'total_touchpoints' => $total,
            'last_seen_at'      => now(),
        ]);

        return $engagement->refresh();
    }

    private function computeScore(int $customerId, CustomerEngagement $engagement): int
    {
        return min(100, $this->recencyScore($engagement) + $this->frequencyScore($customerId) + $this->diversityScore($customerId));
    }

    /** 40 pts max. Decays 2 pts per day of inactivity from the previous last_seen. */
    private function recencyScore(CustomerEngagement $engagement): int
    {
        if ($engagement->last_seen_at === null) {
            return 0;
        }

        $daysSince = (int) $engagement->last_seen_at->diffInDays(now());

        return max(0, 40 - ($daysSince * 2));
    }

    /** 35 pts max. Based on touchpoint count over last 30 days. */
    private function frequencyScore(int $customerId): int
    {
        $count = $this->touchpoints->query()
            ->where('customer_id', $customerId)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return min(35, $count * 5);
    }

    /** 25 pts max. Bonus for variety of touchpoint types. */
    private function diversityScore(int $customerId): int
    {
        $types = $this->touchpoints->query()
            ->where('customer_id', $customerId)
            ->where('created_at', '>=', now()->subDays(30))
            ->distinct()
            ->pluck('type')
            ->count();

        return min(25, $types * 5);
    }

    private function resolveTier(int $score): string
    {
        foreach (self::TIERS as $tier => $threshold) {
            if ($score >= $threshold) {
                return $tier;
            }
        }

        return 'cold';
    }
}
