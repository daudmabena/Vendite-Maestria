<?php

declare(strict_types=1);

use Modules\Checkout\Models\Adjustment;
use Modules\ShopCore\Models\Channel;
use Modules\Checkout\Models\Order;
use Modules\Checkout\Models\OrderItem;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Models\ProductAssociation;
use Modules\Catalog\Models\ProductAssociationType;
use Modules\Catalog\Models\ProductVariant;
use Modules\Promotion\Models\Promotion;
use Modules\Promotion\Models\PromotionAction;
use Modules\Promotion\Models\PromotionCoupon;
use Modules\Promotion\Models\PromotionRule;
use Modules\Catalog\Models\Taxon;
use Modules\Catalog\Models\TaxonTranslation;
use Modules\Promotion\Services\PromotionApplicator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('taxon tree and translations behave like Sylius taxonomy', function () {
    $root = Taxon::query()->create(['code' => 'root', 'enabled' => true]);
    TaxonTranslation::query()->create([
        'taxon_id' => $root->id,
        'locale' => 'en_US',
        'name' => 'Root',
        'slug' => 'root',
    ]);

    $child = Taxon::query()->create(['code' => 'child', 'parent_id' => $root->id, 'enabled' => true]);
    TaxonTranslation::query()->create([
        'taxon_id' => $child->id,
        'locale' => 'en_US',
        'name' => 'Child',
        'slug' => 'child',
    ]);

    expect($child->parent->code)->toBe('root')
        ->and($root->children)->toHaveCount(1)
        ->and($child->translate()?->name)->toBe('Child');
});

test('product association links owner to associated products like Sylius', function () {
    $type = ProductAssociationType::query()->create(['code' => 'related', 'name' => 'Related']);
    $owner = Product::query()->create(['code' => 'owner']);
    $assoc = Product::query()->create(['code' => 'assoc']);

    $link = ProductAssociation::query()->create([
        'owner_product_id' => $owner->id,
        'product_association_type_id' => $type->id,
    ]);

    $link->associatedProducts()->attach($assoc);

    $owner->load('associationsWhereOwner.associatedProducts');

    expect($owner->associationsWhereOwner->first()?->associatedProducts->first()?->code)->toBe('assoc');
});

test('promotion applicator applies percentage discount from rules and actions', function () {
    $channel = Channel::query()->create(['code' => 'ch', 'name' => 'CH']);

    $promotion = Promotion::query()->create([
        'code' => 'p10',
        'name' => '10%',
        'exclusive' => false,
        'priority' => 1,
        'coupon_based' => false,
        'enabled' => true,
    ]);

    $promotion->channels()->attach($channel);

    PromotionRule::query()->create([
        'promotion_id' => $promotion->id,
        'type' => PromotionRule::TYPE_MINIMUM_ORDER_AMOUNT,
        'configuration' => ['amount' => 1000],
    ]);

    PromotionAction::query()->create([
        'promotion_id' => $promotion->id,
        'type' => PromotionAction::TYPE_ORDER_PERCENTAGE_DISCOUNT,
        'configuration' => ['percentage' => 0.10],
    ]);

    $order = Order::query()->create([
        'channel_id' => $channel->id,
        'state' => Order::STATE_CART,
        'items_total' => 2000,
        'adjustments_total' => 0,
        'total' => 2000,
    ]);

    app(PromotionApplicator::class)->apply($order);

    $order->refresh();
    $adj = $order->adjustments()->where('type', Adjustment::ORDER_PROMOTION_ADJUSTMENT)->first();

    expect($adj)->not->toBeNull()
        ->and($adj->amount)->toBe(-200)
        ->and($order->adjustments_total)->toBe(-200);
});

test('contains_taxon rule limits promotion to products in that taxon', function () {
    $channel = Channel::query()->create(['code' => 'ch2', 'name' => 'CH2']);

    $taxon = Taxon::query()->create(['code' => 'books', 'enabled' => true]);
    TaxonTranslation::query()->create([
        'taxon_id' => $taxon->id,
        'locale' => 'en_US',
        'name' => 'Books',
        'slug' => 'books',
    ]);

    $product = Product::query()->create(['code' => 'book-a']);
    $product->taxons()->attach($taxon->id, ['position' => 0]);
    $variant = ProductVariant::query()->create(['product_id' => $product->id, 'code' => 'book-a-v']);

    $promotion = Promotion::query()->create([
        'code' => 'book_only',
        'name' => 'Book promo',
        'coupon_based' => false,
        'enabled' => true,
    ]);
    $promotion->channels()->attach($channel);

    PromotionRule::query()->create([
        'promotion_id' => $promotion->id,
        'type' => PromotionRule::TYPE_CONTAINS_TAXON,
        'configuration' => ['taxon_code' => 'books'],
    ]);

    PromotionAction::query()->create([
        'promotion_id' => $promotion->id,
        'type' => PromotionAction::TYPE_ORDER_FIXED_DISCOUNT,
        'configuration' => ['amount' => 150],
    ]);

    $order = Order::query()->create([
        'channel_id' => $channel->id,
        'items_total' => 500,
        'adjustments_total' => 0,
        'total' => 500,
    ]);

    OrderItem::query()->create([
        'order_id' => $order->id,
        'product_variant_id' => $variant->id,
        'unit_price' => 500,
        'quantity' => 1,
        'units_total' => 500,
        'total' => 500,
    ]);

    app(PromotionApplicator::class)->apply($order);

    expect($order->fresh()->adjustments()->where('type', Adjustment::ORDER_PROMOTION_ADJUSTMENT)->exists())->toBeTrue();
});

test('coupon_based promotion requires order promotion coupon', function () {
    $channel = Channel::query()->create(['code' => 'ch3', 'name' => 'CH3']);

    $promotion = Promotion::query()->create([
        'code' => 'vip',
        'name' => 'VIP',
        'coupon_based' => true,
        'enabled' => true,
    ]);
    $promotion->channels()->attach($channel);

    PromotionRule::query()->create([
        'promotion_id' => $promotion->id,
        'type' => PromotionRule::TYPE_MINIMUM_ORDER_AMOUNT,
        'configuration' => ['amount' => 0],
    ]);

    PromotionAction::query()->create([
        'promotion_id' => $promotion->id,
        'type' => PromotionAction::TYPE_ORDER_FIXED_DISCOUNT,
        'configuration' => ['amount' => 50],
    ]);

    $coupon = PromotionCoupon::query()->create([
        'promotion_id' => $promotion->id,
        'code' => 'VIP50',
    ]);

    $order = Order::query()->create([
        'channel_id' => $channel->id,
        'items_total' => 100,
        'adjustments_total' => 0,
        'total' => 100,
    ]);

    app(PromotionApplicator::class)->apply($order);
    expect($order->fresh()->adjustments()->where('type', Adjustment::ORDER_PROMOTION_ADJUSTMENT)->exists())->toBeFalse();

    $order->update(['promotion_coupon_id' => $coupon->id]);
    app(PromotionApplicator::class)->apply($order);

    expect($order->fresh()->adjustments()->where('type', Adjustment::ORDER_PROMOTION_ADJUSTMENT)->exists())->toBeTrue();
});
