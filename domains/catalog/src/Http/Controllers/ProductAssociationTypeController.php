<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers;

use Modules\Catalog\Repositories\Contracts\ProductAssociationTypeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Http\Requests\StoreProductAssociationTypeRequest;
use Modules\Catalog\Http\Requests\UpdateProductAssociationTypeRequest;

final class ProductAssociationTypeController extends Controller
{
    public function __construct(
        private readonly ProductAssociationTypeRepositoryInterface $productAssociationTypeRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->productAssociationTypeRepository->paginate($perPage));
    }

    public function store(StoreProductAssociationTypeRequest $request): JsonResponse
    {
        $model = $this->productAssociationTypeRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->productAssociationTypeRepository->findOrFail($id));
    }

    public function update(UpdateProductAssociationTypeRequest $request, int $id): JsonResponse
    {
        $model = $this->productAssociationTypeRepository->findOrFail($id);
        $model = $this->productAssociationTypeRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->productAssociationTypeRepository->findOrFail($id);
        $this->productAssociationTypeRepository->delete($model);

        return response()->json(null, 204);
    }
}
