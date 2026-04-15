<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Controllers;

use Modules\ShopCore\Repositories\Contracts\LocaleRepositoryInterface;
use Modules\Checkout\Http\Requests\StoreLocaleRequest;
use Modules\Checkout\Http\Requests\UpdateLocaleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class LocaleController extends Controller
{
    public function __construct(
        private readonly LocaleRepositoryInterface $localeRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->localeRepository->paginate($perPage));
    }

    public function store(StoreLocaleRequest $request): JsonResponse
    {
        $model = $this->localeRepository->create($request->validated());

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->localeRepository->findOrFail($id));
    }

    public function update(UpdateLocaleRequest $request, int $id): JsonResponse
    {
        $model = $this->localeRepository->findOrFail($id);
        $model = $this->localeRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->localeRepository->findOrFail($id);
        $this->localeRepository->delete($model);

        return response()->json(null, 204);
    }
}
