<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Controllers;

use Modules\Checkout\Repositories\Contracts\OrderItemUnitRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreOrderItemUnitRequest;
use Modules\Checkout\Http\Requests\UpdateOrderItemUnitRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class OrderItemUnitController extends Controller
{
    public function __construct(
        private readonly OrderItemUnitRepositoryInterface $orderItemUnitRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->orderItemUnitRepository->paginate($perPage));
    }

    public function store(StoreOrderItemUnitRequest $request): JsonResponse
    {
        $model = $this->orderItemUnitRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->orderItemUnitRepository->findOrFail($id));
    }

    public function update(UpdateOrderItemUnitRequest $request, int $id): JsonResponse
    {
        $model = $this->orderItemUnitRepository->findOrFail($id);
        $model = $this->orderItemUnitRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->orderItemUnitRepository->findOrFail($id);
        $this->orderItemUnitRepository->delete($model);

        return response()->json(null, 204);
    }
}
