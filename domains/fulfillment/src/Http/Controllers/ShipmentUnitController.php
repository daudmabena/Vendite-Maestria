<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Http\Controllers;

use Modules\Fulfillment\Repositories\Contracts\ShipmentUnitRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreShipmentUnitRequest;
use Modules\Checkout\Http\Requests\UpdateShipmentUnitRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class ShipmentUnitController extends Controller
{
    public function __construct(
        private readonly ShipmentUnitRepositoryInterface $shipmentUnitRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->shipmentUnitRepository->paginate($perPage));
    }

    public function store(StoreShipmentUnitRequest $request): JsonResponse
    {
        $model = $this->shipmentUnitRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->shipmentUnitRepository->findOrFail($id));
    }

    public function update(UpdateShipmentUnitRequest $request, int $id): JsonResponse
    {
        $model = $this->shipmentUnitRepository->findOrFail($id);
        $model = $this->shipmentUnitRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->shipmentUnitRepository->findOrFail($id);
        $this->shipmentUnitRepository->delete($model);

        return response()->json(null, 204);
    }
}
