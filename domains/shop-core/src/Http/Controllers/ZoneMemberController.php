<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Controllers;

use Modules\ShopCore\Repositories\Contracts\ZoneMemberRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreZoneMemberRequest;
use Modules\Checkout\Http\Requests\UpdateZoneMemberRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class ZoneMemberController extends Controller
{
    public function __construct(
        private readonly ZoneMemberRepositoryInterface $zoneMemberRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->zoneMemberRepository->paginate($perPage));
    }

    public function store(StoreZoneMemberRequest $request): JsonResponse
    {
        $model = $this->zoneMemberRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->zoneMemberRepository->findOrFail($id));
    }

    public function update(UpdateZoneMemberRequest $request, int $id): JsonResponse
    {
        $model = $this->zoneMemberRepository->findOrFail($id);
        $model = $this->zoneMemberRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->zoneMemberRepository->findOrFail($id);
        $this->zoneMemberRepository->delete($model);

        return response()->json(null, 204);
    }
}
