<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Controllers;

use Modules\Brand\Repositories\Contracts\CampaignRepositoryInterface;
use Modules\Brand\Repositories\Contracts\CustomerEngagementRepositoryInterface;
use Modules\Brand\Repositories\Contracts\TouchpointRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class DashboardController extends Controller
{
    public function __construct(
        private readonly CustomerEngagementRepositoryInterface $engagements,
        private readonly TouchpointRepositoryInterface $touchpoints,
        private readonly CampaignRepositoryInterface $campaigns,
    ) {}

    public function __invoke(): JsonResponse
    {
        $tierDistribution = $this->engagements->tierDistribution()
            ->mapWithKeys(fn ($row) => [$row->trust_tier => (int) $row->count]);

        $dailyTouchpoints = $this->touchpoints->query()
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get()
            ->map(fn ($row) => ['date' => $row->date, 'count' => (int) $row->count]);

        $lapsingCount = $this->engagements->findLapsing(30)->count();

        $topCampaign = $this->campaigns->query()
            ->where('sent_count', '>', 0)
            ->orderByRaw('opened_count / sent_count DESC')
            ->first();

        return response()->json([
            'tier_distribution'  => $tierDistribution,
            'daily_touchpoints'  => $dailyTouchpoints,
            'lapsing_count'      => $lapsingCount,
            'top_campaign'       => $topCampaign,
        ]);
    }
}
