<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers;

use Modules\Customer\Http\Resources\AddressResource;
use Modules\Customer\Models\Address;
use App\Models\User;
use Modules\Customer\Repositories\Contracts\AddressRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreAddressRequest;
use Modules\Checkout\Http\Requests\UpdateAddressRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

final class AddressController extends Controller
{
    public function __construct(
        private readonly AddressRepositoryInterface $addressRepository,
    ) {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        Gate::authorize('viewAny', Address::class);
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        $addresses = Address::query()
            ->where('customer_id', $user->customer->id)
            ->latest('id')
            ->paginate($perPage);

        return response()->json(AddressResource::collection($addresses));
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        Gate::authorize('create', Address::class);
        $payload = $request->safe()->except(['customer_id']);
        $payload['customer_id'] = $user->customer->id;
        $model = $this->addressRepository->create($payload);

        return response()->json(AddressResource::make($model), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $model = $this->addressRepository->findOrFail($id);
        Gate::authorize('view', $model);

        return response()->json(AddressResource::make($model));
    }

    public function update(UpdateAddressRequest $request, int $id): JsonResponse
    {
        $model = $this->addressRepository->findOrFail($id);
        Gate::authorize('update', $model);
        $payload = $request->safe()->except(['customer_id']);
        $model = $this->addressRepository->update($model, array_filter($payload, static fn ($v) => $v !== null));

        return response()->json(AddressResource::make($model));
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->addressRepository->findOrFail($id);
        Gate::authorize('delete', $model);
        $this->addressRepository->delete($model);

        return response()->json(null, 204);
    }
}
