<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers;

use Modules\Catalog\Repositories\Contracts\ProductImageRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Http\Requests\StoreProductImageRequest;
use Modules\Catalog\Http\Requests\UpdateProductImageRequest;

final class ProductImageController extends Controller
{
    public function __construct(
        private readonly ProductImageRepositoryInterface $productImageRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->productImageRepository->paginate($perPage));
    }

    public function store(StoreProductImageRequest $request): JsonResponse
    {
        $model = $this->productImageRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->productImageRepository->findOrFail($id));
    }

    public function update(UpdateProductImageRequest $request, int $id): JsonResponse
    {
        $model = $this->productImageRepository->findOrFail($id);
        $model = $this->productImageRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->productImageRepository->findOrFail($id);
        $this->productImageRepository->delete($model);

        return response()->json(null, 204);
    }
}
