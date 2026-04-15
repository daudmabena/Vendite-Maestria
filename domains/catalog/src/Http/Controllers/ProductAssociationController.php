<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers;

use Modules\Catalog\Repositories\Contracts\ProductAssociationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Http\Requests\StoreProductAssociationRequest;
use Modules\Catalog\Http\Requests\UpdateProductAssociationRequest;

final class ProductAssociationController extends Controller
{
    public function __construct(
        private readonly ProductAssociationRepositoryInterface $productAssociationRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->productAssociationRepository->paginate($perPage));
    }

    public function store(StoreProductAssociationRequest $request): JsonResponse
    {
        $model = $this->productAssociationRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->productAssociationRepository->findOrFail($id));
    }

    public function update(UpdateProductAssociationRequest $request, int $id): JsonResponse
    {
        $model = $this->productAssociationRepository->findOrFail($id);
        $model = $this->productAssociationRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->productAssociationRepository->findOrFail($id);
        $this->productAssociationRepository->delete($model);

        return response()->json(null, 204);
    }
}
