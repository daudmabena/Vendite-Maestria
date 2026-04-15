<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers;

use Modules\Catalog\Repositories\Contracts\ProductOptionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Http\Requests\StoreProductOptionRequest;
use Modules\Catalog\Http\Requests\UpdateProductOptionRequest;

final class ProductOptionController extends Controller
{
    public function __construct(
        private readonly ProductOptionRepositoryInterface $productOptionRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->productOptionRepository->paginate($perPage));
    }

    public function store(StoreProductOptionRequest $request): JsonResponse
    {
        $model = $this->productOptionRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->productOptionRepository->findOrFail($id));
    }

    public function update(UpdateProductOptionRequest $request, int $id): JsonResponse
    {
        $model = $this->productOptionRepository->findOrFail($id);
        $model = $this->productOptionRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->productOptionRepository->findOrFail($id);
        $this->productOptionRepository->delete($model);

        return response()->json(null, 204);
    }
}
