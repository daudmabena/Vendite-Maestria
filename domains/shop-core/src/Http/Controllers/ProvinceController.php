<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Controllers;

use Modules\ShopCore\Repositories\Contracts\ProvinceRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreProvinceRequest;
use Modules\Checkout\Http\Requests\UpdateProvinceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class ProvinceController extends Controller
{
    public function __construct(
        private readonly ProvinceRepositoryInterface $provinceRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->provinceRepository->paginate($perPage));
    }

    public function store(StoreProvinceRequest $request): JsonResponse
    {
        $model = $this->provinceRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->provinceRepository->findOrFail($id));
    }

    public function update(UpdateProvinceRequest $request, int $id): JsonResponse
    {
        $model = $this->provinceRepository->findOrFail($id);
        $model = $this->provinceRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->provinceRepository->findOrFail($id);
        $this->provinceRepository->delete($model);

        return response()->json(null, 204);
    }
}
