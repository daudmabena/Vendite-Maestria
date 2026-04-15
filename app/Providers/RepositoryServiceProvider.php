<?php

declare(strict_types=1);

namespace App\Providers;

use Modules\Customer\Repositories\Contracts\AddressRepositoryInterface;
use Modules\Checkout\Repositories\Contracts\AdjustmentRepositoryInterface;
use Modules\Catalog\Repositories\Contracts\ChannelPricingRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\ChannelRepositoryInterface;
use Modules\Content\Repositories\Contracts\ContactMessageRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\CountryRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\CurrencyRepositoryInterface;
use Modules\Customer\Repositories\Contracts\CustomerGroupRepositoryInterface;
use Modules\Customer\Repositories\Contracts\CustomerRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\LocaleRepositoryInterface;
use Modules\Checkout\Repositories\Contracts\OrderItemRepositoryInterface;
use Modules\Checkout\Repositories\Contracts\OrderItemUnitRepositoryInterface;
use Modules\Checkout\Repositories\Contracts\OrderRepositoryInterface;
use Modules\Checkout\Repositories\Contracts\PaymentMethodRepositoryInterface;
use Modules\Checkout\Repositories\Contracts\PaymentRepositoryInterface;
use Modules\Catalog\Repositories\Contracts\ProductReviewRepositoryInterface;
use Modules\Promotion\Repositories\Contracts\PromotionActionRepositoryInterface;
use Modules\Promotion\Repositories\Contracts\PromotionCouponRepositoryInterface;
use Modules\Promotion\Repositories\Contracts\PromotionRepositoryInterface;
use Modules\Promotion\Repositories\Contracts\PromotionRuleRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\ProvinceRepositoryInterface;
use Modules\Fulfillment\Repositories\Contracts\ShipmentRepositoryInterface;
use Modules\Fulfillment\Repositories\Contracts\ShipmentUnitRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\ShippingCategoryRepositoryInterface;
use Modules\Fulfillment\Repositories\Contracts\ShippingMethodRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\TaxCategoryRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\TaxRateRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\ZoneMemberRepositoryInterface;
use Modules\ShopCore\Repositories\Contracts\ZoneRepositoryInterface;
use Modules\Customer\Repositories\AddressRepository;
use Modules\Checkout\Repositories\AdjustmentRepository;
use Modules\Catalog\Repositories\ChannelPricingRepository;
use Modules\ShopCore\Repositories\ChannelRepository;
use Modules\Content\Repositories\ContactMessageRepository;
use Modules\ShopCore\Repositories\CountryRepository;
use Modules\ShopCore\Repositories\CurrencyRepository;
use Modules\Customer\Repositories\CustomerGroupRepository;
use Modules\Customer\Repositories\CustomerRepository;
use Modules\ShopCore\Repositories\LocaleRepository;
use Modules\Checkout\Repositories\OrderItemRepository;
use Modules\Checkout\Repositories\OrderItemUnitRepository;
use Modules\Checkout\Repositories\OrderRepository;
use Modules\Checkout\Repositories\PaymentMethodRepository;
use Modules\Checkout\Repositories\PaymentRepository;
use Modules\Catalog\Repositories\ProductReviewRepository;
use Modules\Promotion\Repositories\PromotionActionRepository;
use Modules\Promotion\Repositories\PromotionCouponRepository;
use Modules\Promotion\Repositories\PromotionRepository;
use Modules\Promotion\Repositories\PromotionRuleRepository;
use Modules\ShopCore\Repositories\ProvinceRepository;
use Modules\Fulfillment\Repositories\ShipmentRepository;
use Modules\Fulfillment\Repositories\ShipmentUnitRepository;
use Modules\ShopCore\Repositories\ShippingCategoryRepository;
use Modules\Fulfillment\Repositories\ShippingMethodRepository;
use Modules\ShopCore\Repositories\TaxCategoryRepository;
use Modules\ShopCore\Repositories\TaxRateRepository;
use Modules\ShopCore\Repositories\ZoneMemberRepository;
use Modules\ShopCore\Repositories\ZoneRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);
        $this->app->bind(AdjustmentRepositoryInterface::class, AdjustmentRepository::class);
        $this->app->bind(ChannelPricingRepositoryInterface::class, ChannelPricingRepository::class);
        $this->app->bind(ChannelRepositoryInterface::class, ChannelRepository::class);
        $this->app->bind(ContactMessageRepositoryInterface::class, ContactMessageRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(CurrencyRepositoryInterface::class, CurrencyRepository::class);
        $this->app->bind(CustomerGroupRepositoryInterface::class, CustomerGroupRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(LocaleRepositoryInterface::class, LocaleRepository::class);
        $this->app->bind(OrderItemRepositoryInterface::class, OrderItemRepository::class);
        $this->app->bind(OrderItemUnitRepositoryInterface::class, OrderItemUnitRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(PaymentMethodRepositoryInterface::class, PaymentMethodRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(ProductReviewRepositoryInterface::class, ProductReviewRepository::class);
        $this->app->bind(PromotionActionRepositoryInterface::class, PromotionActionRepository::class);
        $this->app->bind(PromotionCouponRepositoryInterface::class, PromotionCouponRepository::class);
        $this->app->bind(PromotionRepositoryInterface::class, PromotionRepository::class);
        $this->app->bind(PromotionRuleRepositoryInterface::class, PromotionRuleRepository::class);
        $this->app->bind(ProvinceRepositoryInterface::class, ProvinceRepository::class);
        $this->app->bind(ShipmentRepositoryInterface::class, ShipmentRepository::class);
        $this->app->bind(ShipmentUnitRepositoryInterface::class, ShipmentUnitRepository::class);
        $this->app->bind(ShippingCategoryRepositoryInterface::class, ShippingCategoryRepository::class);
        $this->app->bind(ShippingMethodRepositoryInterface::class, ShippingMethodRepository::class);
        $this->app->bind(TaxCategoryRepositoryInterface::class, TaxCategoryRepository::class);
        $this->app->bind(TaxRateRepositoryInterface::class, TaxRateRepository::class);
        $this->app->bind(ZoneMemberRepositoryInterface::class, ZoneMemberRepository::class);
        $this->app->bind(ZoneRepositoryInterface::class, ZoneRepository::class);
    }
}
