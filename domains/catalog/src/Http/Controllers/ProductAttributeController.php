<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers;

use Modules\Catalog\Repositories\Contracts\ProductAttributeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Http\Requests\StoreProductAttributeRequest;
use Modules\Catalog\Http\Requests\UpdateProductAttributeRequest;

final class ProductAttributeController extends Controller
{
    public function __construct(
        private readonly ProductAttributeRepositoryInterface $productAttributeRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->productAttributeRepository->paginate($perPage));
    }

    public function store(StoreProductAttributeRequest $request): JsonResponse
    {
        $model = $this->productAttributeRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->productAttributeRepository->findOrFail($id));
    }

    public function update(UpdateProductAttributeRequest $request, int $id): JsonResponse
    {
        $model = $this->productAttributeRepository->findOrFail($id);
        $model = $this->productAttributeRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->productAttributeRepository->findOrFail($id);
        $this->productAttributeRepository->delete($model);

        return response()->json(null, 204);
    }
}
