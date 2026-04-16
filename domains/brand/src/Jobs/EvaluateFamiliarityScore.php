<?php

declare(strict_types=1);

namespace Modules\Brand\Jobs;

use Modules\Brand\Services\FamiliarityScoreService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class EvaluateFamiliarityScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int $customerId,
    ) {}

    public function handle(FamiliarityScoreService $service): void
    {
        $service->recalculate($this->customerId);
    }
}
