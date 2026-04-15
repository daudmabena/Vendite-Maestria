<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Controllers;

use Modules\ShopCore\Repositories\Contracts\ZoneRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreZoneRequest;
use Modules\Checkout\Http\Requests\UpdateZoneRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class ZoneController extends Controller
{
    public function __construct(
        private readonly ZoneRepositoryInterface $zoneRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->zoneRepository->paginate($perPage));
    }

    public function store(StoreZoneRequest $request): JsonResponse
    {
        $model = $this->zoneRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->zoneRepository->findOrFail($id));
    }

    public function update(UpdateZoneRequest $request, int $id): JsonResponse
    {
        $model = $this->zoneRepository->findOrFail($id);
        $model = $this->zoneRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->zoneRepository->findOrFail($id);
        $this->zoneRepository->delete($model);

        return response()->json(null, 204);
    }
}
