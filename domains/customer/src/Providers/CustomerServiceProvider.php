<?php

namespace Modules\Customer\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Customer\Models\Address;
use Modules\Customer\Policies\AddressPolicy;
use Modules\Customer\Repositories\AddressRepository;
use Modules\Customer\Repositories\Contracts\AddressRepositoryInterface;
use Modules\Customer\Repositories\Contracts\CustomerGroupRepositoryInterface;
use Modules\Customer\Repositories\Contracts\CustomerRepositoryInterface;
use Modules\Customer\Repositories\CustomerGroupRepository;
use Modules\Customer\Repositories\CustomerRepository;

class CustomerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);
        $this->app->bind(CustomerGroupRepositoryInterface::class, CustomerGroupRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(dirname(__DIR__, 2).'/routes/customer-routes.php');
        $this->loadMigrationsFrom(dirname(__DIR__, 2).'/database/migrations');
        Gate::policy(Address::class, AddressPolicy::class);
    }
}
