<?php

namespace App\Providers;

use Modules\Customer\Models\Address;
use Modules\Checkout\Models\Order;
use Modules\Customer\Policies\AddressPolicy;
use Modules\Checkout\Policies\OrderPolicy;
use Modules\Checkout\Services\CartService;
use Modules\Checkout\Services\CartTotalsRefresher;
use Modules\ShopCore\Services\ShopContext;
use Modules\Checkout\Services\OrderIdentifierGenerator;
use Modules\Checkout\Services\PaymentProcessor;
use Modules\ShopCore\Services\PricingContextResolver;
use Modules\Promotion\Services\PromotionApplicator;
use Modules\Checkout\Workflow\OrderWorkflow;
use Modules\Checkout\Workflow\PaymentWorkflow;
use Modules\Fulfillment\Workflow\ShipmentWorkflow;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PaymentProcessor::class);
        $this->app->singleton(PromotionApplicator::class);
        $this->app->singleton(CartTotalsRefresher::class);
        $this->app->singleton(CartService::class);
        $this->app->singleton(OrderWorkflow::class);
        $this->app->singleton(PaymentWorkflow::class);
        $this->app->singleton(ShipmentWorkflow::class);
        $this->app->scoped(ShopContext::class);
        $this->app->singleton(PricingContextResolver::class);
        $this->app->singleton(OrderIdentifierGenerator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Address::class, AddressPolicy::class);
    }
}
