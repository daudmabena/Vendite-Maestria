<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers;

use Modules\Catalog\Models\ProductReview;
use App\Models\User;
use Modules\Catalog\Repositories\Contracts\ProductReviewRepositoryInterface;
use Modules\Catalog\Http\Requests\StoreProductReviewRequest;
use Modules\Catalog\Http\Requests\UpdateProductReviewRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class ProductReviewController extends Controller
{
    public function __construct(
        private readonly ProductReviewRepositoryInterface $productReviewRepository,
    ) {
        $this->middleware('auth:sanctum')->only(['store']);
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json(
            ProductReview::query()
                ->where('status', ProductReview::STATUS_ACCEPTED)
                ->latest('id')
                ->paginate($perPage),
        );
    }

    public function store(StoreProductReviewRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $customer = $user->customer;
        if ($customer === null) {
            abort(403, 'Customer profile is required.');
        }

        $payload = $request->validated();
        $payload['customer_id'] = $customer->id;
        $payload['status'] = ProductReview::STATUS_PENDING;
        $payload['accepted_at'] = null;
        $payload['rejected_at'] = null;

        $model = $this->productReviewRepository->create($payload);

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->productReviewRepository->findOrFail($id));
    }

    public function update(UpdateProductReviewRequest $request, int $id): JsonResponse
    {
        $model = $this->productReviewRepository->findOrFail($id);
        $model = $this->productReviewRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->productReviewRepository->findOrFail($id);
        $this->productReviewRepository->delete($model);

        return response()->json(null, 204);
    }
}
