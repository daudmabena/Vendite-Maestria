<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers;

use Modules\Customer\Repositories\Contracts\CustomerGroupRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreCustomerGroupRequest;
use Modules\Checkout\Http\Requests\UpdateCustomerGroupRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class CustomerGroupController extends Controller
{
    public function __construct(
        private readonly CustomerGroupRepositoryInterface $customerGroupRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->customerGroupRepository->paginate($perPage));
    }

    public function store(StoreCustomerGroupRequest $request): JsonResponse
    {
        $model = $this->customerGroupRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->customerGroupRepository->findOrFail($id));
    }

    public function update(UpdateCustomerGroupRequest $request, int $id): JsonResponse
    {
        $model = $this->customerGroupRepository->findOrFail($id);
        $model = $this->customerGroupRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->customerGroupRepository->findOrFail($id);
        $this->customerGroupRepository->delete($model);

        return response()->json(null, 204);
    }
}
