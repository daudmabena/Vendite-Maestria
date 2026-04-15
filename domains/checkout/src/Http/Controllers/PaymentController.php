<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Controllers;

use Modules\Checkout\Repositories\Contracts\PaymentRepositoryInterface;
use Modules\Checkout\Http\Requests\StorePaymentRequest;
use Modules\Checkout\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentRepositoryInterface $paymentRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->paymentRepository->paginate($perPage));
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        $model = $this->paymentRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->paymentRepository->findOrFail($id));
    }

    public function update(UpdatePaymentRequest $request, int $id): JsonResponse
    {
        $model = $this->paymentRepository->findOrFail($id);
        $model = $this->paymentRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->paymentRepository->findOrFail($id);
        $this->paymentRepository->delete($model);

        return response()->json(null, 204);
    }
}
