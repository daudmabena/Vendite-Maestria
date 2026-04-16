<?php

declare(strict_types=1);

namespace Modules\Brand\Providers;

use Modules\Brand\Jobs\TriggerWinBackCampaign;
use Modules\Brand\Repositories\CampaignRepository;
use Modules\Brand\Repositories\Contracts\CampaignRepositoryInterface;
use Modules\Brand\Repositories\Contracts\CustomerEngagementRepositoryInterface;
use Modules\Brand\Repositories\Contracts\TouchpointRepositoryInterface;
use Modules\Brand\Repositories\CustomerEngagementRepository;
use Modules\Brand\Repositories\TouchpointRepository;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class BrandServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TouchpointRepositoryInterface::class, TouchpointRepository::class);
        $this->app->bind(CustomerEngagementRepositoryInterface::class, CustomerEngagementRepository::class);
        $this->app->bind(CampaignRepositoryInterface::class, CampaignRepository::class);

        $this->app->register(BrandEventServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(dirname(__DIR__, 2).'/routes/brand-routes.php');
        $this->loadMigrationsFrom(dirname(__DIR__, 2).'/database/migrations');

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            // Daily win-back pass at 09:00 — notifies customers silent for 30+ days.
            $schedule->job(TriggerWinBackCampaign::class)->dailyAt('09:00');
        });
    }
}
