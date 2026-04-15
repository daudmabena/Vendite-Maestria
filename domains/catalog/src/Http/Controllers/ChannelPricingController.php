<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Http\Requests\StoreChannelPricingRequest;
use Modules\Catalog\Http\Requests\UpdateChannelPricingRequest;
use Modules\Catalog\Repositories\Contracts\ChannelPricingRepositoryInterface;

final class ChannelPricingController extends Controller
{
    public function __construct(
        private readonly ChannelPricingRepositoryInterface $channelPricingRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->channelPricingRepository->paginate($perPage));
    }

    public function store(StoreChannelPricingRequest $request): JsonResponse
    {
        $model = $this->channelPricingRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->channelPricingRepository->findOrFail($id));
    }

    public function update(UpdateChannelPricingRequest $request, int $id): JsonResponse
    {
        $model = $this->channelPricingRepository->findOrFail($id);
        $model = $this->channelPricingRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->channelPricingRepository->findOrFail($id);
        $this->channelPricingRepository->delete($model);

        return response()->json(null, 204);
    }
}
