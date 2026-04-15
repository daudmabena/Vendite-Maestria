<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Controllers;

use Modules\Checkout\Repositories\Contracts\AdjustmentRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreAdjustmentRequest;
use Modules\Checkout\Http\Requests\UpdateAdjustmentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class AdjustmentController extends Controller
{
    public function __construct(
        private readonly AdjustmentRepositoryInterface $adjustmentRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->adjustmentRepository->paginate($perPage));
    }

    public function store(StoreAdjustmentRequest $request): JsonResponse
    {
        $model = $this->adjustmentRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->adjustmentRepository->findOrFail($id));
    }

    public function update(UpdateAdjustmentRequest $request, int $id): JsonResponse
    {
        $model = $this->adjustmentRepository->findOrFail($id);
        $model = $this->adjustmentRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->adjustmentRepository->findOrFail($id);
        $this->adjustmentRepository->delete($model);

        return response()->json(null, 204);
    }
}
