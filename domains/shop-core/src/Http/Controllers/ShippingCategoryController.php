<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Controllers;

use Modules\ShopCore\Repositories\Contracts\ShippingCategoryRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreShippingCategoryRequest;
use Modules\Checkout\Http\Requests\UpdateShippingCategoryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class ShippingCategoryController extends Controller
{
    public function __construct(
        private readonly ShippingCategoryRepositoryInterface $shippingCategoryRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->shippingCategoryRepository->paginate($perPage));
    }

    public function store(StoreShippingCategoryRequest $request): JsonResponse
    {
        $model = $this->shippingCategoryRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->shippingCategoryRepository->findOrFail($id));
    }

    public function update(UpdateShippingCategoryRequest $request, int $id): JsonResponse
    {
        $model = $this->shippingCategoryRepository->findOrFail($id);
        $model = $this->shippingCategoryRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->shippingCategoryRepository->findOrFail($id);
        $this->shippingCategoryRepository->delete($model);

        return response()->json(null, 204);
    }
}
