<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Controllers;

use Modules\ShopCore\Repositories\Contracts\TaxCategoryRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreTaxCategoryRequest;
use Modules\Checkout\Http\Requests\UpdateTaxCategoryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class TaxCategoryController extends Controller
{
    public function __construct(
        private readonly TaxCategoryRepositoryInterface $taxCategoryRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->taxCategoryRepository->paginate($perPage));
    }

    public function store(StoreTaxCategoryRequest $request): JsonResponse
    {
        $model = $this->taxCategoryRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->taxCategoryRepository->findOrFail($id));
    }

    public function update(UpdateTaxCategoryRequest $request, int $id): JsonResponse
    {
        $model = $this->taxCategoryRepository->findOrFail($id);
        $model = $this->taxCategoryRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->taxCategoryRepository->findOrFail($id);
        $this->taxCategoryRepository->delete($model);

        return response()->json(null, 204);
    }
}
