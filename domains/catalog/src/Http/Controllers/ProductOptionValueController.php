<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers;

use Modules\Catalog\Repositories\Contracts\ProductOptionValueRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Http\Requests\StoreProductOptionValueRequest;
use Modules\Catalog\Http\Requests\UpdateProductOptionValueRequest;

final class ProductOptionValueController extends Controller
{
    public function __construct(
        private readonly ProductOptionValueRepositoryInterface $productOptionValueRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->productOptionValueRepository->paginate($perPage));
    }

    public function store(StoreProductOptionValueRequest $request): JsonResponse
    {
        $model = $this->productOptionValueRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->productOptionValueRepository->findOrFail($id));
    }

    public function update(UpdateProductOptionValueRequest $request, int $id): JsonResponse
    {
        $model = $this->productOptionValueRepository->findOrFail($id);
        $model = $this->productOptionValueRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->productOptionValueRepository->findOrFail($id);
        $this->productOptionValueRepository->delete($model);

        return response()->json(null, 204);
    }
}
