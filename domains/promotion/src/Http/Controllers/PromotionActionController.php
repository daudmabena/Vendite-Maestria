<?php

declare(strict_types=1);

namespace Modules\Promotion\Http\Controllers;

use Modules\Promotion\Repositories\Contracts\PromotionActionRepositoryInterface;
use Modules\Checkout\Http\Requests\StorePromotionActionRequest;
use Modules\Checkout\Http\Requests\UpdatePromotionActionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class PromotionActionController extends Controller
{
    public function __construct(
        private readonly PromotionActionRepositoryInterface $promotionActionRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->promotionActionRepository->paginate($perPage));
    }

    public function store(StorePromotionActionRequest $request): JsonResponse
    {
        $model = $this->promotionActionRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->promotionActionRepository->findOrFail($id));
    }

    public function update(UpdatePromotionActionRequest $request, int $id): JsonResponse
    {
        $model = $this->promotionActionRepository->findOrFail($id);
        $model = $this->promotionActionRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->promotionActionRepository->findOrFail($id);
        $this->promotionActionRepository->delete($model);

        return response()->json(null, 204);
    }
}
