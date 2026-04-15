<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Controllers;

use Modules\ShopCore\Repositories\Contracts\CountryRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreCountryRequest;
use Modules\Checkout\Http\Requests\UpdateCountryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class CountryController extends Controller
{
    public function __construct(
        private readonly CountryRepositoryInterface $countryRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->countryRepository->paginate($perPage));
    }

    public function store(StoreCountryRequest $request): JsonResponse
    {
        $model = $this->countryRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->countryRepository->findOrFail($id));
    }

    public function update(UpdateCountryRequest $request, int $id): JsonResponse
    {
        $model = $this->countryRepository->findOrFail($id);
        $model = $this->countryRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->countryRepository->findOrFail($id);
        $this->countryRepository->delete($model);

        return response()->json(null, 204);
    }
}
