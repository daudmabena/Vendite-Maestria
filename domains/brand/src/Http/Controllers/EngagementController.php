<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Controllers;

use Modules\Brand\Repositories\Contracts\CustomerEngagementRepositoryInterface;
use Modules\Brand\Repositories\Contracts\TouchpointRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class EngagementController extends Controller
{
    public function __construct(
        private readonly CustomerEngagementRepositoryInterface $engagements,
        private readonly TouchpointRepositoryInterface $touchpoints,
    ) {}

    public function show(int $customerId): JsonResponse
    {
        $engagement  = $this->engagements->findOrCreateForCustomer($customerId);
        $timeline    = $this->touchpoints->forCustomer($customerId, 20);
        $typeCounts  = $this->touchpoints->countByTypeForCustomer($customerId, 30)
            ->mapWithKeys(fn ($r) => [$r->type => (int) $r->count]);

        return response()->json([
            'engagement'  => $engagement,
            'timeline'    => $timeline,
            'type_counts' => $typeCounts,
        ]);
    }
}
