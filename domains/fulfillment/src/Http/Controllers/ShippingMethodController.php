<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Http\Controllers;

use Modules\Fulfillment\Repositories\Contracts\ShippingMethodRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreShippingMethodRequest;
use Modules\Checkout\Http\Requests\UpdateShippingMethodRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class ShippingMethodController extends Controller
{
    public function __construct(
        private readonly ShippingMethodRepositoryInterface $shippingMethodRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->shippingMethodRepository->paginate($perPage));
    }

    public function store(StoreShippingMethodRequest $request): JsonResponse
    {
        $model = $this->shippingMethodRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->shippingMethodRepository->findOrFail($id));
    }

    public function update(UpdateShippingMethodRequest $request, int $id): JsonResponse
    {
        $model = $this->shippingMethodRepository->findOrFail($id);
        $model = $this->shippingMethodRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->shippingMethodRepository->findOrFail($id);
        $this->shippingMethodRepository->delete($model);

        return response()->json(null, 204);
    }
}
