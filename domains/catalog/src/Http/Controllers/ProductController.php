<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers;

use Modules\Catalog\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Http\Requests\StoreProductRequest;
use Modules\Catalog\Http\Requests\UpdateProductRequest;

final class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->productRepository->paginate($perPage));
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $model = $this->productRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->productRepository->findOrFail($id));
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $model = $this->productRepository->findOrFail($id);
        $model = $this->productRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->productRepository->findOrFail($id);
        $this->productRepository->delete($model);

        return response()->json(null, 204);
    }
}
