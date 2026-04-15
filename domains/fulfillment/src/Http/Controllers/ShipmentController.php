<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Http\Controllers;

use Modules\Fulfillment\Repositories\Contracts\ShipmentRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreShipmentRequest;
use Modules\Checkout\Http\Requests\UpdateShipmentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class ShipmentController extends Controller
{
    public function __construct(
        private readonly ShipmentRepositoryInterface $shipmentRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->shipmentRepository->paginate($perPage));
    }

    public function store(StoreShipmentRequest $request): JsonResponse
    {
        $model = $this->shipmentRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->shipmentRepository->findOrFail($id));
    }

    public function update(UpdateShipmentRequest $request, int $id): JsonResponse
    {
        $model = $this->shipmentRepository->findOrFail($id);
        $model = $this->shipmentRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->shipmentRepository->findOrFail($id);
        $this->shipmentRepository->delete($model);

        return response()->json(null, 204);
    }
}
