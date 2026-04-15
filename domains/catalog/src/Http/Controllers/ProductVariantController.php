<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers;

use Modules\Catalog\Repositories\Contracts\ProductVariantRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Http\Requests\StoreProductVariantRequest;
use Modules\Catalog\Http\Requests\UpdateProductVariantRequest;

final class ProductVariantController extends Controller
{
    public function __construct(
        private readonly ProductVariantRepositoryInterface $productVariantRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->productVariantRepository->paginate($perPage));
    }

    public function store(StoreProductVariantRequest $request): JsonResponse
    {
        $model = $this->productVariantRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->productVariantRepository->findOrFail($id));
    }

    public function update(UpdateProductVariantRequest $request, int $id): JsonResponse
    {
        $model = $this->productVariantRepository->findOrFail($id);
        $model = $this->productVariantRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->productVariantRepository->findOrFail($id);
        $this->productVariantRepository->delete($model);

        return response()->json(null, 204);
    }
}
