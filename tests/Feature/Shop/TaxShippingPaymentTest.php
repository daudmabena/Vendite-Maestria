<?php

declare(strict_types=1);

use Modules\Checkout\Models\Order;
use Modules\Checkout\Models\OrderItem;
use Modules\Checkout\Models\OrderItemUnit;
use Modules\Checkout\Models\Payment;
use Modules\Checkout\Models\PaymentMethod;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Models\ProductVariant;
use Modules\Fulfillment\Models\Shipment;
use Modules\Fulfillment\Models\ShipmentUnit;
use Modules\ShopCore\Models\ShippingCategory;
use Modules\Fulfillment\Models\ShippingMethod;
use Modules\ShopCore\Models\TaxCategory;
use Modules\ShopCore\Models\TaxRate;
use Modules\Checkout\Services\PaymentProcessor;
use Modules\Fulfillment\Services\FlatRateShippingCalculator;
use Modules\Promotion\Services\DefaultTaxCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('default tax calculator matches Sylius fraction rates', function () {
    $category = TaxCategory::query()->create(['code' => 't1', 'name' => 'T1']);
    $rate = TaxRate::query()->create([
        'tax_category_id' => $category->id,
        'code' => 'r1',
        'name' => '10%',
        'amount' => 0.10,
        'included_in_price' => false,
        'calculator' => 'default',
    ]);

    $calc = new DefaultTaxCalculator;

    expect($calc->calculateExclusiveMinorUnits(1000, $rate))->toBe(100)
        ->and($rate->getAmountAsPercentage())->toBe(10.0);
});

test('flat rate shipping reads Sylius-style configuration', function () {
    $cat = ShippingCategory::query()->create(['code' => 'sc', 'name' => 'SC']);
    $method = ShippingMethod::query()->create([
        'code' => 'flat',
        'name' => 'Flat',
        'shipping_category_id' => $cat->id,
        'calculator' => 'flat_rate',
        'configuration' => ['amount' => 499],
        'enabled' => true,
    ]);

    expect(FlatRateShippingCalculator::amountMinor($method))->toBe(499);
});

test('manual payment driver completes without Payum', function () {
    $order = Order::query()->create(['state' => Order::STATE_CART]);
    $pm = PaymentMethod::query()->create([
        'code' => 'manual',
        'name' => 'Manual',
        'driver' => PaymentMethod::DRIVER_MANUAL,
        'enabled' => true,
    ]);

    $payment = Payment::query()->create([
        'order_id' => $order->id,
        'payment_method_id' => $pm->id,
        'currency_code' => 'USD',
        'amount' => 2000,
        'state' => Payment::STATE_CART,
    ]);

    $result = app(PaymentProcessor::class)->beginPayment($payment);

    expect($result['status'])->toBe('completed')
        ->and($payment->fresh()->state)->toBe(Payment::STATE_COMPLETED);
});

test('stripe driver uses HTTP client and sets processing state when configured', function () {
    config(['services.stripe.secret' => 'sk_test_fake']);

    Http::fake([
        'api.stripe.com/v1/payment_intents' => Http::response([
            'id' => 'pi_test',
            'client_secret' => 'cs_test_secret',
        ], 200),
    ]);

    $order = Order::query()->create(['state' => Order::STATE_CART]);
    $pm = PaymentMethod::query()->create([
        'code' => 'stripe',
        'name' => 'Stripe',
        'driver' => PaymentMethod::DRIVER_STRIPE,
        'enabled' => true,
    ]);

    $payment = Payment::query()->create([
        'order_id' => $order->id,
        'payment_method_id' => $pm->id,
        'currency_code' => 'USD',
        'amount' => 1500,
        'state' => Payment::STATE_CART,
    ]);

    $result = app(PaymentProcessor::class)->beginPayment($payment);

    expect($result['status'])->toBe('processing')
        ->and($result['client_secret'])->toBe('cs_test_secret')
        ->and($payment->fresh()->state)->toBe(Payment::STATE_PROCESSING)
        ->and($payment->fresh()->details['payment_intent_id'] ?? null)->toBe('pi_test');
});

test('shipment can reference order item units like Sylius', function () {
    $order = Order::query()->create(['state' => Order::STATE_CART]);
    $product = Product::query()->create(['code' => 'ship-p']);
    $variant = ProductVariant::query()->create(['product_id' => $product->id, 'code' => 'ship-v']);
    $item = OrderItem::query()->create([
        'order_id' => $order->id,
        'product_variant_id' => $variant->id,
        'unit_price' => 100,
    ]);
    $unit = OrderItemUnit::query()->create(['order_item_id' => $item->id]);

    $shipment = Shipment::query()->create([
        'order_id' => $order->id,
        'state' => Shipment::STATE_CART,
    ]);

    ShipmentUnit::query()->create([
        'shipment_id' => $shipment->id,
        'order_item_unit_id' => $unit->id,
    ]);

    $shipment->load('units.orderItemUnit');

    expect($shipment->units)->toHaveCount(1)
        ->and($shipment->units->first()->orderItemUnit->id)->toBe($unit->id);
});
