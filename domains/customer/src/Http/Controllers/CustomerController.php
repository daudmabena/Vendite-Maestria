<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers;

use Modules\Customer\Http\Resources\CustomerResource;
use App\Models\User;
use Modules\Customer\Repositories\Contracts\CustomerRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreCustomerRequest;
use Modules\Checkout\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class CustomerController extends Controller
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
    ) {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $customer = $user->customer;
        if ($customer === null) {
            abort(403, 'Customer profile is required.');
        }

        return response()->json(CustomerResource::collection(collect([$customer])));
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        if ($user->customer !== null) {
            abort(422, 'Customer profile already exists.');
        }

        $payload = $request->safe()->except(['user_id']);
        $payload['user_id'] = $user->id;
        $payload['email'] = $payload['email'] ?? $user->email;
        $model = $this->customerRepository->create($payload);

        return response()->json(CustomerResource::make($model), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $model = $this->customerRepository->findOrFail($id);

        if ($model->user_id !== $user->id) {
            abort(403);
        }

        return response()->json(CustomerResource::make($model));
    }

    public function update(UpdateCustomerRequest $request, int $id): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $model = $this->customerRepository->findOrFail($id);

        if ($model->user_id !== $user->id) {
            abort(403);
        }

        $payload = $request->safe()->except(['user_id']);
        $model = $this->customerRepository->update($model, array_filter($payload, static fn ($v) => $v !== null));

        return response()->json(CustomerResource::make($model));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $model = $this->customerRepository->findOrFail($id);

        if ($model->user_id !== $user->id) {
            abort(403);
        }

        $this->customerRepository->delete($model);

        return response()->json(null, 204);
    }
}
