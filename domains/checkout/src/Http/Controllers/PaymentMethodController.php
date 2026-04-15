<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Controllers;

use Modules\Checkout\Repositories\Contracts\PaymentMethodRepositoryInterface;
use Modules\Checkout\Http\Requests\StorePaymentMethodRequest;
use Modules\Checkout\Http\Requests\UpdatePaymentMethodRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class PaymentMethodController extends Controller
{
    public function __construct(
        private readonly PaymentMethodRepositoryInterface $paymentMethodRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->paymentMethodRepository->paginate($perPage));
    }

    public function store(StorePaymentMethodRequest $request): JsonResponse
    {
        $model = $this->paymentMethodRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->paymentMethodRepository->findOrFail($id));
    }

    public function update(UpdatePaymentMethodRequest $request, int $id): JsonResponse
    {
        $model = $this->paymentMethodRepository->findOrFail($id);
        $model = $this->paymentMethodRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->paymentMethodRepository->findOrFail($id);
        $this->paymentMethodRepository->delete($model);

        return response()->json(null, 204);
    }
}
