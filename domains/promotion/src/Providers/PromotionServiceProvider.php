<?php

namespace Modules\Promotion\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Promotion\Repositories\Contracts\PromotionActionRepositoryInterface;
use Modules\Promotion\Repositories\Contracts\PromotionCouponRepositoryInterface;
use Modules\Promotion\Repositories\Contracts\PromotionRepositoryInterface;
use Modules\Promotion\Repositories\Contracts\PromotionRuleRepositoryInterface;
use Modules\Promotion\Repositories\PromotionActionRepository;
use Modules\Promotion\Repositories\PromotionCouponRepository;
use Modules\Promotion\Repositories\PromotionRepository;
use Modules\Promotion\Repositories\PromotionRuleRepository;

class PromotionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PromotionActionRepositoryInterface::class, PromotionActionRepository::class);
        $this->app->bind(PromotionCouponRepositoryInterface::class, PromotionCouponRepository::class);
        $this->app->bind(PromotionRepositoryInterface::class, PromotionRepository::class);
        $this->app->bind(PromotionRuleRepositoryInterface::class, PromotionRuleRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(dirname(__DIR__, 2).'/routes/promotion-routes.php');
        $this->loadMigrationsFrom(dirname(__DIR__, 2).'/database/migrations');
    }
}
