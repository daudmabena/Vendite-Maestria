<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Controllers;

use Modules\ShopCore\Repositories\Contracts\CurrencyRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreCurrencyRequest;
use Modules\Checkout\Http\Requests\UpdateCurrencyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class CurrencyController extends Controller
{
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencyRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->currencyRepository->paginate($perPage));
    }

    public function store(StoreCurrencyRequest $request): JsonResponse
    {
        $model = $this->currencyRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->currencyRepository->findOrFail($id));
    }

    public function update(UpdateCurrencyRequest $request, int $id): JsonResponse
    {
        $model = $this->currencyRepository->findOrFail($id);
        $model = $this->currencyRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->currencyRepository->findOrFail($id);
        $this->currencyRepository->delete($model);

        return response()->json(null, 204);
    }
}
