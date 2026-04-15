<?php

declare(strict_types=1);

namespace Modules\Promotion\Http\Controllers;

use Modules\Promotion\Repositories\Contracts\PromotionCouponRepositoryInterface;
use Modules\Checkout\Http\Requests\StorePromotionCouponRequest;
use Modules\Checkout\Http\Requests\UpdatePromotionCouponRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class PromotionCouponController extends Controller
{
    public function __construct(
        private readonly PromotionCouponRepositoryInterface $promotionCouponRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->promotionCouponRepository->paginate($perPage));
    }

    public function store(StorePromotionCouponRequest $request): JsonResponse
    {
        $model = $this->promotionCouponRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->promotionCouponRepository->findOrFail($id));
    }

    public function update(UpdatePromotionCouponRequest $request, int $id): JsonResponse
    {
        $model = $this->promotionCouponRepository->findOrFail($id);
        $model = $this->promotionCouponRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->promotionCouponRepository->findOrFail($id);
        $this->promotionCouponRepository->delete($model);

        return response()->json(null, 204);
    }
}
