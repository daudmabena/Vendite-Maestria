<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Controllers;

use Modules\Brand\Http\Requests\StoreCampaignRequest;
use Modules\Brand\Http\Requests\UpdateCampaignRequest;
use Modules\Brand\Repositories\Contracts\CampaignRepositoryInterface;
use Modules\Brand\Services\CampaignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class CampaignController extends Controller
{
    public function __construct(
        private readonly CampaignRepositoryInterface $campaigns,
        private readonly CampaignService $service,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->campaigns->paginate($perPage));
    }

    public function store(StoreCampaignRequest $request): JsonResponse
    {
        $campaign = $this->service->create($request->validated());

        return response()->json($campaign, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->campaigns->findOrFail($id));
    }

    public function update(UpdateCampaignRequest $request, int $id): JsonResponse
    {
        $campaign = $this->campaigns->findOrFail($id);
        $updated  = $this->service->update(
            $campaign,
            array_filter($request->validated(), static fn ($v) => $v !== null),
        );

        return response()->json($updated);
    }

    public function launch(int $id): JsonResponse
    {
        $campaign = $this->campaigns->findOrFail($id);
        $launched = $this->service->launch($campaign);

        return response()->json($launched);
    }

    public function destroy(int $id): JsonResponse
    {
        $campaign = $this->campaigns->findOrFail($id);
        $this->campaigns->delete($campaign);

        return response()->json(null, 204);
    }
}
