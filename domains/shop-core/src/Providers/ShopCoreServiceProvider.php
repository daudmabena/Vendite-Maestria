<?php

namespace Modules\ShopCore\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ShopCore\Repositories\ChannelRepository;
use Modules\ShopCore\Repositories\Contracts\ChannelRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\CountryRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\CurrencyRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\LocaleRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\ProvinceRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\ShippingCategoryRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\TaxCategoryRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\TaxRateRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\ZoneMemberRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\ZoneRepositoryInterface;
use Modules\ShopCore\Repositories\CountryRepository;
use Modules\ShopCore\Repositories\CurrencyRepository;
use Modules\ShopCore\Repositories\LocaleRepository;
use Modules\ShopCore\Repositories\ProvinceRepository;
use Modules\ShopCore\Repositories\ShippingCategoryRepository;
use Modules\ShopCore\Repositories\TaxCategoryRepository;
use Modules\ShopCore\Repositories\TaxRateRepository;
use Modules\ShopCore\Repositories\ZoneMemberRepository;
use Modules\ShopCore\Repositories\ZoneRepository;

class ShopCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ChannelRepositoryInterface::class, ChannelRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(CurrencyRepositoryInterface::class, CurrencyRepository::class);
        $this->app->bind(LocaleRepositoryInterface::class, LocaleRepository::class);
        $this->app->bind(ProvinceRepositoryInterface::class, ProvinceRepository::class);
        $this->app->bind(ShippingCategoryRepositoryInterface::class, ShippingCategoryRepository::class);
        $this->app->bind(TaxCategoryRepositoryInterface::class, TaxCategoryRepository::class);
        $this->app->bind(TaxRateRepositoryInterface::class, TaxRateRepository::class);
        $this->app->bind(ZoneMemberRepositoryInterface::class, ZoneMemberRepository::class);
        $this->app->bind(ZoneRepositoryInterface::class, ZoneRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(dirname(__DIR__, 2).'/routes/shop-core-routes.php');
        $this->loadMigrationsFrom(dirname(__DIR__, 2).'/database/migrations');
    }
}
