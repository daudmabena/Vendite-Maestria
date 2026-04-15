<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Controllers;

use Modules\Checkout\Repositories\Contracts\OrderItemRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreOrderItemRequest;
use Modules\Checkout\Http\Requests\UpdateOrderItemRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class OrderItemController extends Controller
{
    public function __construct(
        private readonly OrderItemRepositoryInterface $orderItemRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->orderItemRepository->paginate($perPage));
    }

    public function store(StoreOrderItemRequest $request): JsonResponse
    {
        $model = $this->orderItemRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->orderItemRepository->findOrFail($id));
    }

    public function update(UpdateOrderItemRequest $request, int $id): JsonResponse
    {
        $model = $this->orderItemRepository->findOrFail($id);
        $model = $this->orderItemRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->orderItemRepository->findOrFail($id);
        $this->orderItemRepository->delete($model);

        return response()->json(null, 204);
    }
}
