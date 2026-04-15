<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Controllers;

use Modules\ShopCore\Repositories\Contracts\ChannelRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreChannelRequest;
use Modules\Checkout\Http\Requests\UpdateChannelRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class ChannelController extends Controller
{
    public function __construct(
        private readonly ChannelRepositoryInterface $channelRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->channelRepository->paginate($perPage));
    }

    public function store(StoreChannelRequest $request): JsonResponse
    {
        $model = $this->channelRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->channelRepository->findOrFail($id));
    }

    public function update(UpdateChannelRequest $request, int $id): JsonResponse
    {
        $model = $this->channelRepository->findOrFail($id);
        $model = $this->channelRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->channelRepository->findOrFail($id);
        $this->channelRepository->delete($model);

        return response()->json(null, 204);
    }
}
