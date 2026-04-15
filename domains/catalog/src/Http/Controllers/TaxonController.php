<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers;

use Modules\Catalog\Repositories\Contracts\TaxonRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Http\Requests\StoreTaxonRequest;
use Modules\Catalog\Http\Requests\UpdateTaxonRequest;

final class TaxonController extends Controller
{
    public function __construct(
        private readonly TaxonRepositoryInterface $taxonRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->taxonRepository->paginate($perPage));
    }

    public function store(StoreTaxonRequest $request): JsonResponse
    {
        $model = $this->taxonRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->taxonRepository->findOrFail($id));
    }

    public function update(UpdateTaxonRequest $request, int $id): JsonResponse
    {
        $model = $this->taxonRepository->findOrFail($id);
        $model = $this->taxonRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->taxonRepository->findOrFail($id);
        $this->taxonRepository->delete($model);

        return response()->json(null, 204);
    }
}
