<?php

declare(strict_types=1);

namespace Database\Seeders;

use Modules\ShopCore\Models\Channel;
use Modules\Catalog\Models\ChannelPricing;
use Modules\Customer\Models\Customer;
use Modules\ShopCore\Models\Country;
use Modules\ShopCore\Models\Currency;
use Modules\Customer\Models\CustomerGroup;
use Modules\ShopCore\Models\Locale;
use Modules\Checkout\Models\Order;
use Modules\Checkout\Models\OrderItem;
use Modules\Checkout\Models\OrderItemUnit;
use Modules\Checkout\Models\PaymentMethod;
use Modules\Catalog\Models\ProductAssociation;
use Modules\Catalog\Models\ProductAssociationType;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Models\ProductTranslation;
use Modules\Catalog\Models\ProductVariant;
use Modules\Catalog\Models\ProductVariantTranslation;
use Modules\Checkout\Models\Adjustment;
use Modules\Customer\Models\Address;
use Modules\Promotion\Models\Promotion;
use Modules\Promotion\Models\PromotionAction;
use Modules\Promotion\Models\PromotionRule;
use Modules\ShopCore\Models\Province;
use Modules\ShopCore\Models\ShippingCategory;
use Modules\Fulfillment\Models\ShippingMethod;
use Modules\ShopCore\Models\TaxCategory;
use Modules\ShopCore\Models\TaxRate;
use Modules\Catalog\Models\Taxon;
use Modules\Catalog\Models\TaxonTranslation;
use Modules\ShopCore\Models\Zone;
use Modules\ShopCore\Models\ZoneMember;
use Illuminate\Database\Seeder;

class ShopCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $usd = Currency::query()->firstOrCreate(['code' => 'USD']);
        $en = Locale::query()->firstOrCreate(['code' => 'en_US']);

        $channel = Channel::query()->firstOrCreate(
            ['code' => 'default'],
            [
                'name' => 'Default channel',
                'description' => 'Migrated-from-Sylius-style default storefront',
                'enabled' => true,
                'base_currency_id' => $usd->id,
                'default_locale_id' => $en->id,
            ],
        );

        if (! $channel->currencies()->whereKey($usd->id)->exists()) {
            $channel->currencies()->attach($usd);
        }
        if (! $channel->locales()->whereKey($en->id)->exists()) {
            $channel->locales()->attach($en);
        }

        $us = Country::query()->firstOrCreate(
            ['code' => 'US'],
            ['enabled' => true],
        );

        Province::query()->firstOrCreate(
            ['country_id' => $us->id, 'code' => 'CA'],
            ['name' => 'California', 'abbreviation' => 'CA'],
        );

        if (! $channel->countries()->whereKey($us->id)->exists()) {
            $channel->countries()->attach($us);
        }

        CustomerGroup::query()->firstOrCreate(
            ['code' => 'retail'],
            ['name' => 'Retail'],
        );

        $usZone = Zone::query()->firstOrCreate(
            ['code' => 'us'],
            [
                'name' => 'United States',
                'type' => Zone::TYPE_COUNTRY,
                'scope' => Zone::SCOPE_ALL,
                'priority' => 0,
            ],
        );

        ZoneMember::query()->firstOrCreate(
            [
                'zone_id' => $usZone->id,
                'code' => 'US',
            ],
        );

        $taxCategory = TaxCategory::query()->firstOrCreate(
            ['code' => 'standard'],
            [
                'name' => 'Standard',
                'description' => 'Default taxable goods',
            ],
        );

        TaxRate::query()->firstOrCreate(
            ['code' => 'us-sales'],
            [
                'tax_category_id' => $taxCategory->id,
                'name' => 'US sales tax',
                'amount' => 0.10,
                'included_in_price' => false,
                'calculator' => 'default',
            ],
        );

        $shippingCategory = ShippingCategory::query()->firstOrCreate(
            ['code' => 'default'],
            [
                'name' => 'Default',
                'description' => 'General merchandise',
            ],
        );

        $flatShipping = ShippingMethod::query()->firstOrCreate(
            ['code' => 'flat_us'],
            [
                'name' => 'Flat rate (US)',
                'description' => 'Sylius-style flat rate shipping',
                'shipping_category_id' => $shippingCategory->id,
                'zone_id' => $usZone->id,
                'calculator' => 'flat_rate',
                'configuration' => ['amount' => 500],
                'position' => 0,
                'enabled' => true,
            ],
        );

        if (! $flatShipping->channels()->whereKey($channel->id)->exists()) {
            $flatShipping->channels()->attach($channel);
        }

        $manualPayment = PaymentMethod::query()->firstOrCreate(
            ['code' => 'cash_on_delivery'],
            [
                'name' => 'Cash on delivery',
                'driver' => PaymentMethod::DRIVER_MANUAL,
                'gateway_config' => [],
                'enabled' => true,
            ],
        );

        $stripePayment = PaymentMethod::query()->firstOrCreate(
            ['code' => 'stripe'],
            [
                'name' => 'Stripe',
                'driver' => PaymentMethod::DRIVER_STRIPE,
                'gateway_config' => ['note' => 'Set STRIPE_SECRET for PaymentIntents'],
                'enabled' => true,
            ],
        );

        foreach ([$manualPayment, $stripePayment] as $method) {
            if (! $method->channels()->whereKey($channel->id)->exists()) {
                $method->channels()->attach($channel);
            }
        }

        $product = Product::query()->firstOrCreate(
            ['code' => 'demo-sku'],
            ['enabled' => true, 'variant_selection_method' => Product::VARIANT_SELECTION_CHOICE],
        );

        ProductTranslation::query()->firstOrCreate(
            [
                'product_id' => $product->id,
                'locale' => 'en_US',
            ],
            [
                'name' => 'Demo product',
                'slug' => 'demo-product',
                'description' => 'Sample catalog row for Laravel port.',
            ],
        );

        $variant = ProductVariant::query()->firstOrCreate(
            [
                'product_id' => $product->id,
                'code' => 'demo-sku-variant',
            ],
            ['enabled' => true, 'position' => 0],
        );

        ProductVariantTranslation::query()->firstOrCreate(
            [
                'product_variant_id' => $variant->id,
                'locale' => 'en_US',
            ],
            ['name' => 'Default'],
        );

        $variant->update([
            'tracked' => true,
            'on_hand' => 100,
            'on_hold' => 0,
            'tax_category_id' => $taxCategory->id,
            'shipping_category_id' => $shippingCategory->id,
        ]);

        ChannelPricing::query()->firstOrCreate(
            [
                'product_variant_id' => $variant->id,
                'channel_id' => $channel->id,
            ],
            [
                'price' => 1000,
                'original_price' => 1200,
                'minimum_price' => 500,
            ],
        );

        if (! $channel->products()->whereKey($product->id)->exists()) {
            $channel->products()->attach($product);
        }

        $menuTaxon = Taxon::query()->firstOrCreate(
            ['code' => 'menu'],
            ['enabled' => true, 'position' => 0],
        );

        TaxonTranslation::query()->firstOrCreate(
            ['taxon_id' => $menuTaxon->id, 'locale' => 'en_US'],
            ['name' => 'Menu', 'slug' => 'menu', 'description' => 'Root menu'],
        );

        $demoCategoryTaxon = Taxon::query()->firstOrCreate(
            ['code' => 'demo-category'],
            [
                'parent_id' => $menuTaxon->id,
                'enabled' => true,
                'position' => 0,
            ],
        );

        TaxonTranslation::query()->firstOrCreate(
            ['taxon_id' => $demoCategoryTaxon->id, 'locale' => 'en_US'],
            [
                'name' => 'Demo category',
                'slug' => 'demo-category',
                'description' => 'Seeded taxon for catalog',
            ],
        );

        $product->update(['main_taxon_id' => $demoCategoryTaxon->id]);

        if (! $product->taxons()->whereKey($demoCategoryTaxon->id)->exists()) {
            $product->taxons()->attach($demoCategoryTaxon->id, ['position' => 0]);
        }

        $relatedType = ProductAssociationType::query()->firstOrCreate(
            ['code' => 'related'],
            ['name' => 'Related products'],
        );

        ProductAssociation::query()->firstOrCreate(
            [
                'owner_product_id' => $product->id,
                'product_association_type_id' => $relatedType->id,
            ],
        );

        $catalogPromotion = Promotion::query()->firstOrCreate(
            ['code' => 'ten_pct_carts'],
            [
                'name' => '10% off qualifying carts',
                'description' => 'Items subtotal ≥ 500 (minor units); channel-scoped',
                'exclusive' => false,
                'priority' => 10,
                'coupon_based' => false,
                'enabled' => true,
            ],
        );

        if (! $catalogPromotion->channels()->whereKey($channel->id)->exists()) {
            $catalogPromotion->channels()->attach($channel);
        }

        PromotionRule::query()->firstOrCreate(
            [
                'promotion_id' => $catalogPromotion->id,
                'type' => PromotionRule::TYPE_MINIMUM_ORDER_AMOUNT,
            ],
            ['configuration' => ['amount' => 500]],
        );

        PromotionAction::query()->firstOrCreate(
            [
                'promotion_id' => $catalogPromotion->id,
                'type' => PromotionAction::TYPE_ORDER_PERCENTAGE_DISCOUNT,
            ],
            ['configuration' => ['percentage' => 0.10]],
        );

        $customer = Customer::query()->firstOrCreate(
            ['email' => 'shopper@example.com'],
            [
                'first_name' => 'Demo',
                'last_name' => 'Shopper',
                'gender' => Customer::GENDER_UNKNOWN,
            ],
        );

        $address = Address::query()->firstOrCreate(
            [
                'customer_id' => $customer->id,
                'street' => '1 Market St',
                'city' => 'San Francisco',
                'postcode' => '94105',
            ],
            [
                'first_name' => 'Demo',
                'last_name' => 'Shopper',
                'country_code' => 'US',
                'province_code' => 'CA',
            ],
        );

        $customer->setDefaultAddress($address);

        $order = Order::query()->firstOrCreate(
            ['number' => 'SYL-ORDER-0001'],
            [
                'customer_id' => $customer->id,
                'channel_id' => $channel->id,
                'billing_address_id' => $address->id,
                'shipping_address_id' => $address->id,
                'currency_code' => 'USD',
                'locale_code' => 'en_US',
                'state' => Order::STATE_CART,
            ],
        );

        $item = OrderItem::query()->firstOrCreate(
            ['order_id' => $order->id, 'product_variant_id' => $variant->id],
            [
                'product_name' => 'Demo product',
                'variant_name' => 'Default',
                'unit_price' => 1000,
            ],
        );

        if (! $item->units()->exists()) {
            OrderItemUnit::query()->create(['order_item_id' => $item->id]);
        }

        if (! $item->adjustments()->where('type', Adjustment::ORDER_ITEM_PROMOTION_ADJUSTMENT)->exists()) {
            $item->adjustments()->create([
                'type' => Adjustment::ORDER_ITEM_PROMOTION_ADJUSTMENT,
                'label' => 'Demo discount',
                'amount' => -100,
                'neutral' => false,
            ]);
        }

        if (! $order->adjustments()->where('type', Adjustment::SHIPPING_ADJUSTMENT)->exists()) {
            $order->adjustments()->create([
                'type' => Adjustment::SHIPPING_ADJUSTMENT,
                'label' => 'Flat shipping',
                'amount' => 250,
                'neutral' => false,
            ]);
        }

        $item->recalculateAdjustmentsTotal();
        $item->recalculateUnitsTotal();
        $order->recalculateAdjustmentsTotal();
        $order->recalculateItemsTotal();
    }
}
