<?php

declare(strict_types=1);

use Modules\Checkout\Models\Adjustment;
use Modules\Customer\Models\Customer;
use Modules\Checkout\Models\Order;
use Modules\Checkout\Models\OrderItem;
use Modules\Checkout\Models\OrderItemUnit;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cart totals include item and order adjustments like Sylius', function () {
    $customer = Customer::query()->create(['email' => 'cart@example.com']);
    $product = Product::query()->create(['code' => 'cart-sku']);
    $variant = ProductVariant::query()->create(['product_id' => $product->id, 'code' => 'cart-variant']);

    $order = Order::query()->create([
        'customer_id' => $customer->id,
        'state' => Order::STATE_CART,
    ]);

    $item = OrderItem::query()->create([
        'order_id' => $order->id,
        'product_variant_id' => $variant->id,
        'unit_price' => 1000,
    ]);

    OrderItemUnit::query()->create(['order_item_id' => $item->id]);
    OrderItemUnit::query()->create(['order_item_id' => $item->id]);

    $item->adjustments()->create([
        'type' => Adjustment::ORDER_ITEM_PROMOTION_ADJUSTMENT,
        'label' => 'item discount',
        'amount' => -200,
        'neutral' => false,
    ]);

    $order->adjustments()->create([
        'type' => Adjustment::SHIPPING_ADJUSTMENT,
        'label' => 'shipping',
        'amount' => 300,
        'neutral' => false,
    ]);

    $order->refresh();
    $item->refresh();

    expect($item->quantity)->toBe(2)
        ->and($item->units_total)->toBe(2000)
        ->and($item->adjustments_total)->toBe(-200)
        ->and($item->total)->toBe(1800)
        ->and($order->items_total)->toBe(1800)
        ->and($order->adjustments_total)->toBe(300)
        ->and($order->total)->toBe(2100)
        ->and($order->isEmpty())->toBeFalse()
        ->and($order->canBeProcessed())->toBeTrue();
});

test('neutral adjustment does not change totals', function () {
    $order = Order::query()->create(['state' => Order::STATE_CART]);
    $order->adjustments()->create([
        'type' => Adjustment::TAX_ADJUSTMENT,
        'label' => 'VAT neutral',
        'amount' => 500,
        'neutral' => true,
    ]);

    expect($order->fresh()->adjustments_total)->toBe(0)
        ->and($order->fresh()->total)->toBe(0);
});

test('checkout completion marks order as completed', function () {
    $order = Order::query()->create(['state' => Order::STATE_CART]);
    expect($order->isCheckoutCompleted())->toBeFalse();

    $order->completeCheckout();

    expect($order->fresh()->isCheckoutCompleted())->toBeTrue();
});
