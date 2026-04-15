<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Controllers;

use Modules\ShopCore\Repositories\Contracts\TaxRateRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreTaxRateRequest;
use Modules\Checkout\Http\Requests\UpdateTaxRateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class TaxRateController extends Controller
{
    public function __construct(
        private readonly TaxRateRepositoryInterface $taxRateRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->taxRateRepository->paginate($perPage));
    }

    public function store(StoreTaxRateRequest $request): JsonResponse
    {
        $model = $this->taxRateRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->taxRateRepository->findOrFail($id));
    }

    public function update(UpdateTaxRateRequest $request, int $id): JsonResponse
    {
        $model = $this->taxRateRepository->findOrFail($id);
        $model = $this->taxRateRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->taxRateRepository->findOrFail($id);
        $this->taxRateRepository->delete($model);

        return response()->json(null, 204);
    }
}
