<?php

declare(strict_types=1);

namespace Modules\Promotion\Http\Controllers;

use Modules\Promotion\Repositories\Contracts\PromotionRuleRepositoryInterface;
use Modules\Checkout\Http\Requests\StorePromotionRuleRequest;
use Modules\Checkout\Http\Requests\UpdatePromotionRuleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class PromotionRuleController extends Controller
{
    public function __construct(
        private readonly PromotionRuleRepositoryInterface $promotionRuleRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->promotionRuleRepository->paginate($perPage));
    }

    public function store(StorePromotionRuleRequest $request): JsonResponse
    {
        $model = $this->promotionRuleRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->promotionRuleRepository->findOrFail($id));
    }

    public function update(UpdatePromotionRuleRequest $request, int $id): JsonResponse
    {
        $model = $this->promotionRuleRepository->findOrFail($id);
        $model = $this->promotionRuleRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->promotionRuleRepository->findOrFail($id);
        $this->promotionRuleRepository->delete($model);

        return response()->json(null, 204);
    }
}
