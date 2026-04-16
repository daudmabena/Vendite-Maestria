<?php

declare(strict_types=1);

namespace Modules\Brand\Services;

use Modules\Brand\Jobs\EvaluateFamiliarityScore;
use Modules\Brand\Repositories\Contracts\TouchpointRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

final class TouchpointService
{
    public function __construct(
        private readonly TouchpointRepositoryInterface $touchpoints,
    ) {}

    /**
     * Record a customer interaction and schedule a score re-evaluation.
     *
     * @param array<string, mixed> $metadata
     */
    public function record(
        int $customerId,
        string $type,
        string $source,
        ?Model $entity = null,
        array $metadata = [],
    ): void {
        $this->touchpoints->create([
            'customer_id' => $customerId,
            'type'        => $type,
            'source'      => $source,
            'entity_type' => $entity ? $entity->getMorphClass() : null,
            'entity_id'   => $entity?->getKey(),
            'metadata'    => empty($metadata) ? null : $metadata,
        ]);

        EvaluateFamiliarityScore::dispatch($customerId);
    }
}
