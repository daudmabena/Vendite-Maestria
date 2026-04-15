<?php

namespace Modules\Checkout\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Checkout\Models\Order;
use Modules\Checkout\Policies\OrderPolicy;
use Modules\Checkout\Repositories\AdjustmentRepository;
use Modules\Checkout\Repositories\Contracts\AdjustmentRepositoryInterface;
use Modules\Checkout\Repositories\Contracts\OrderItemRepositoryInterface;
use Modules\Checkout\Repositories\Contracts\OrderItemUnitRepositoryInterface;
use Modules\Checkout\Repositories\Contracts\OrderRepositoryInterface;
use Modules\Checkout\Repositories\Contracts\PaymentMethodRepositoryInterface;
use Modules\Checkout\Repositories\Contracts\PaymentRepositoryInterface;
use Modules\Checkout\Repositories\OrderItemRepository;
use Modules\Checkout\Repositories\OrderItemUnitRepository;
use Modules\Checkout\Repositories\OrderRepository;
use Modules\Checkout\Repositories\PaymentMethodRepository;
use Modules\Checkout\Repositories\PaymentRepository;

class CheckoutServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AdjustmentRepositoryInterface::class, AdjustmentRepository::class);
        $this->app->bind(OrderItemRepositoryInterface::class, OrderItemRepository::class);
        $this->app->bind(OrderItemUnitRepositoryInterface::class, OrderItemUnitRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(PaymentMethodRepositoryInterface::class, PaymentMethodRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(dirname(__DIR__, 2).'/routes/checkout-routes.php');
        $this->loadMigrationsFrom(dirname(__DIR__, 2).'/database/migrations');
        Gate::policy(Order::class, OrderPolicy::class);
    }
}
