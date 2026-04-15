<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers;

use Modules\Content\Models\ContactMessage;
use Modules\Content\Repositories\Contracts\ContactMessageRepositoryInterface;
use Modules\Content\Http\Requests\StoreContactMessageRequest;
use Modules\Content\Http\Requests\UpdateContactMessageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class ContactMessageController extends Controller
{
    public function __construct(
        private readonly ContactMessageRepositoryInterface $contactMessageRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->contactMessageRepository->paginate($perPage));
    }

    public function store(StoreContactMessageRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $payload['status'] = ContactMessage::STATUS_NEW;
        $payload['resolved_at'] = null;

        $model = $this->contactMessageRepository->create($payload);

        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->contactMessageRepository->findOrFail($id));
    }

    public function update(UpdateContactMessageRequest $request, int $id): JsonResponse
    {
        $model = $this->contactMessageRepository->findOrFail($id);
        $model = $this->contactMessageRepository->update($model, array_filter($request->validated(), static fn ($v) => $v !== null));

        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->contactMessageRepository->findOrFail($id);
        $this->contactMessageRepository->delete($model);

        return response()->json(null, 204);
    }
}
