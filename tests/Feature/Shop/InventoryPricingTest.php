<?php

declare(strict_types=1);

use Modules\ShopCore\Models\Channel;
use Modules\Catalog\Models\ChannelPricing;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Models\ProductTranslation;
use Modules\Catalog\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('tracked stock sufficiency matches Sylius AvailabilityChecker', function () {
    $product = Product::query()->create(['code' => 'inv-1']);
    $variant = ProductVariant::query()->create([
        'product_id' => $product->id,
        'code' => 'inv-1-v',
        'tracked' => true,
        'on_hand' => 5,
        'on_hold' => 2,
    ]);

    expect($variant->isStockSufficient(3))->toBeTrue()
        ->and($variant->isStockSufficient(4))->toBeFalse()
        ->and($variant->isInStock())->toBeTrue();
});

test('untracked variant is always stock sufficient', function () {
    $product = Product::query()->create(['code' => 'inv-2']);
    $variant = ProductVariant::query()->create([
        'product_id' => $product->id,
        'code' => 'inv-2-v',
        'tracked' => false,
        'on_hand' => 0,
        'on_hold' => 0,
    ]);

    expect($variant->isStockSufficient(999))->toBeTrue();
});

test('channel pricing is per variant and channel with Sylius-style price fields', function () {
    $channelA = Channel::query()->create(['code' => 'A', 'name' => 'A']);
    $channelB = Channel::query()->create(['code' => 'B', 'name' => 'B']);

    $product = Product::query()->create(['code' => 'p-dual']);
    $variant = ProductVariant::query()->create(['product_id' => $product->id, 'code' => 'v-dual']);

    ChannelPricing::query()->create([
        'product_variant_id' => $variant->id,
        'channel_id' => $channelA->id,
        'price' => 1000,
        'original_price' => 1500,
        'minimum_price' => 800,
    ]);

    ChannelPricing::query()->create([
        'product_variant_id' => $variant->id,
        'channel_id' => $channelB->id,
        'price' => 900,
        'original_price' => null,
        'minimum_price' => 0,
    ]);

    $pricingA = $variant->getChannelPricingForChannel($channelA);
    $pricingB = $variant->getChannelPricingForChannel($channelB);

    expect($pricingA)->not->toBeNull()
        ->and($pricingA->isPriceReduced())->toBeTrue()
        ->and($variant->priceForChannel($channelA))->toBe(1000)
        ->and($variant->priceForChannel($channelB))->toBe(900)
        ->and($pricingB->isPriceReduced())->toBeFalse();
});

test('inventory name falls back to product translation then variant code', function () {
    $product = Product::query()->create(['code' => 'p-name']);
    ProductTranslation::query()->create([
        'product_id' => $product->id,
        'locale' => 'en_US',
        'name' => 'Widget',
        'slug' => 'widget',
    ]);

    $variant = ProductVariant::query()->create(['product_id' => $product->id, 'code' => 'W-RED']);

    expect($variant->getInventoryName())->toBe('Widget');
});
