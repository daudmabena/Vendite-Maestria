<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Controllers;

use Modules\Checkout\Http\Resources\OrderResource;
use Modules\Checkout\Models\Order;
use App\Models\User;
use Modules\Checkout\Repositories\Contracts\OrderRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreOrderRequest;
use Modules\Checkout\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

final class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
    ) {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        Gate::authorize('viewAny', Order::class);
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        $orders = Order::query()
            ->where('customer_id', $user->customer->id)
            ->latest('id')
            ->paginate($perPage);

        return response()->json(OrderResource::collection($orders));
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        Gate::authorize('create', Order::class);
        $payload = $request->safe()->except(['customer_id']);
        $payload['customer_id'] = $user->customer->id;
        $model = $this->orderRepository->create($payload);

        return response()->json(OrderResource::make($model), 201);
    }

    public function show(int $id): JsonResponse
    {
        $model = $this->orderRepository->findOrFail($id);
        Gate::authorize('view', $model);

        return response()->json(OrderResource::make($model));
    }

    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        $model = $this->orderRepository->findOrFail($id);
        Gate::authorize('update', $model);
        $payload = $request->safe()->except(['customer_id']);
        $model = $this->orderRepository->update($model, array_filter($payload, static fn ($v) => $v !== null));

        return response()->json(OrderResource::make($model));
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->orderRepository->findOrFail($id);
        Gate::authorize('delete', $model);
        $this->orderRepository->delete($model);

        return response()->json(null, 204);
    }
}
