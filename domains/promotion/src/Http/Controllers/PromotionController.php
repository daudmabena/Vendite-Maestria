<?php

declare(strict_types=1);

namespace Modules\Promotion\Http\Controllers;

use Modules\Promotion\Repositories\Contracts\PromotionRepositoryInterface;
use Modules\Checkout\Http\Requests\StorePromotionRequest;
use Modules\Checkout\Http\Requests\UpdatePromotionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class PromotionController extends Controller
{
    public function __construct(
        private readonly PromotionRepositoryInterface $promotionRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->promotionRepository->paginate($perPage));
    }

    public function store(StorePromotionRequest $request): JsonResponse
    {
        $model = $this->promotionRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->promotionRepository->findOrFail($id));
    }

    public function update(UpdatePromotionRequest $request, int $id): JsonResponse
    {
        $model = $this->promotionRepository->findOrFail($id);
        $model = $this->promotionRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->promotionRepository->findOrFail($id);
        $this->promotionRepository->delete($model);

        return response()->json(null, 204);
    }
}
