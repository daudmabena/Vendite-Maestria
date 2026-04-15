<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers;

use Modules\Catalog\Repositories\Contracts\ProductAttributeValueRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Http\Requests\StoreProductAttributeValueRequest;
use Modules\Catalog\Http\Requests\UpdateProductAttributeValueRequest;

final class ProductAttributeValueController extends Controller
{
    public function __construct(
        private readonly ProductAttributeValueRepositoryInterface $productAttributeValueRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->productAttributeValueRepository->paginate($perPage));
    }

    public function store(StoreProductAttributeValueRequest $request): JsonResponse
    {
        $model = $this->productAttributeValueRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->productAttributeValueRepository->findOrFail($id));
    }

    public function update(UpdateProductAttributeValueRequest $request, int $id): JsonResponse
    {
        $model = $this->productAttributeValueRepository->findOrFail($id);
        $model = $this->productAttributeValueRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->productAttributeValueRepository->findOrFail($id);
        $this->productAttributeValueRepository->delete($model);

        return response()->json(null, 204);
    }
}
