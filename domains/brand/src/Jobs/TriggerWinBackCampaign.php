<?php

declare(strict_types=1);

namespace Modules\Brand\Jobs;

use Modules\Brand\Services\WinBackService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

final class TriggerWinBackCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int $silentDays = 30,
    ) {}

    public function handle(WinBackService $service): void
    {
        $sent = $service->runWinBackPass($this->silentDays);

        Log::info("[Brand] Win-back pass complete. Notifications sent: {$sent}");
    }
}
