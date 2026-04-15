<?php

namespace Modules\Fulfillment\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Fulfillment\Repositories\Contracts\ShipmentRepositoryInterface;
use Modules\Fulfillment\Repositories\Contracts\ShipmentUnitRepositoryInterface;
use Modules\Fulfillment\Repositories\Contracts\ShippingMethodRepositoryInterface;
use Modules\Fulfillment\Repositories\ShipmentRepository;
use Modules\Fulfillment\Repositories\ShipmentUnitRepository;
use Modules\Fulfillment\Repositories\ShippingMethodRepository;

class FulfillmentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ShipmentRepositoryInterface::class, ShipmentRepository::class);
        $this->app->bind(ShipmentUnitRepositoryInterface::class, ShipmentUnitRepository::class);
        $this->app->bind(ShippingMethodRepositoryInterface::class, ShippingMethodRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(dirname(__DIR__, 2).'/routes/fulfillment-routes.php');
        $this->loadMigrationsFrom(dirname(__DIR__, 2).'/database/migrations');
    }
}
