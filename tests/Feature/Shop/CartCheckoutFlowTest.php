<?php

declare(strict_types=1);

use Modules\ShopCore\Models\Channel;
use Modules\Catalog\Models\ChannelPricing;
use Modules\Customer\Models\Customer;
use Modules\ShopCore\Models\Currency;
use Modules\ShopCore\Models\Locale;
use Modules\Checkout\Models\Order;
use Modules\Checkout\Models\Payment;
use Modules\Checkout\Models\PaymentMethod;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Models\ProductVariant;
use Modules\ShopCore\Models\ShippingCategory;
use Modules\Fulfillment\Models\ShippingMethod;
use Modules\ShopCore\Models\TaxCategory;
use Modules\ShopCore\Models\TaxRate;
use App\Models\User;
use Modules\Checkout\Notifications\OrderConfirmationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('cart API creates cart, adds lines, applies shipping and tax, completes checkout', function () {
    Notification::fake();

    $user = User::factory()->create();
    Customer::query()->create([
        'user_id' => $user->id,
        'email' => $user->email,
    ]);
    Sanctum::actingAs($user);

    $currency = Currency::query()->create(['code' => 'USD']);
    $locale = Locale::query()->create(['code' => 'en_US']);

    $channel = Channel::query()->create([
        'code' => 'us-web',
        'name' => 'US Web',
        'base_currency_id' => $currency->id,
        'default_locale_id' => $locale->id,
        'enabled' => true,
    ]);
    $channel->currencies()->attach($currency);
    $channel->locales()->attach($locale);

    $product = Product::query()->create(['code' => 'tee', 'enabled' => true]);
    $channel->products()->attach($product);

    $taxCategory = TaxCategory::query()->create(['code' => 'general', 'name' => 'General']);
    TaxRate::query()->create([
        'tax_category_id' => $taxCategory->id,
        'code' => 'sales',
        'name' => 'Sales tax',
        'amount' => 0.10,
        'included_in_price' => false,
        'calculator' => 'default',
    ]);

    $variant = ProductVariant::query()->create([
        'product_id' => $product->id,
        'code' => 'tee-m',
        'enabled' => true,
        'tracked' => true,
        'on_hand' => 10,
        'on_hold' => 0,
        'tax_category_id' => $taxCategory->id,
    ]);

    ChannelPricing::query()->create([
        'product_variant_id' => $variant->id,
        'channel_id' => $channel->id,
        'price' => 1000,
    ]);

    $shipCat = ShippingCategory::query()->create(['code' => 'default', 'name' => 'Default']);
    $variant->update(['shipping_category_id' => $shipCat->id]);

    $shippingMethod = ShippingMethod::query()->create([
        'code' => 'flat-us',
        'name' => 'Flat US',
        'shipping_category_id' => $shipCat->id,
        'calculator' => 'flat_rate',
        'configuration' => ['amount' => 500],
        'enabled' => true,
    ]);
    $channel->shippingMethods()->attach($shippingMethod);

    $paymentMethod = PaymentMethod::query()->create([
        'code' => 'manual',
        'name' => 'Manual',
        'driver' => PaymentMethod::DRIVER_MANUAL,
        'enabled' => true,
    ]);
    $channel->paymentMethods()->attach($paymentMethod);

    $create = $this->postJson('/api/v1/shop/carts', ['channel_id' => $channel->id]);
    $create->assertCreated();
    $token = $create->json('data.token');
    expect($token)->not->toBeEmpty();

    $add = $this->postJson("/api/v1/shop/carts/{$token}/items", [
        'product_variant_id' => $variant->id,
        'quantity' => 2,
    ]);
    $add->assertOk();
    expect($add->json('data.items.0.quantity'))->toBe(2)
        ->and($add->json('data.items.0.unit_price'))->toBe(1000);

    $ship = $this->putJson("/api/v1/shop/carts/{$token}/shipping-method", [
        'shipping_method_id' => $shippingMethod->id,
    ]);
    $ship->assertOk();

    $payload = $ship->json('data');
    expect($payload['total'])->toBe(2700);

    $checkout = $this->postJson("/api/v1/shop/carts/{$token}/checkout", [
        'payment_method_id' => $paymentMethod->id,
    ]);
    $checkout->assertOk();

    expect($checkout->json('payment.state'))->toBe(Payment::STATE_COMPLETED);

    $order = Order::query()->where('token_value', $token)->first();
    expect($order)->not->toBeNull()
        ->and($order->state)->toBe(Order::STATE_NEW)
        ->and($order->isCheckoutCompleted())->toBeTrue()
        ->and($order->number)->toStartWith('SYL-');

    expect($variant->fresh()->on_hold)->toBe(2);
    Notification::assertSentTo($user, OrderConfirmationNotification::class);
});

test('cart API rejects product not linked to channel', function () {
    $user = User::factory()->create();
    Customer::query()->create([
        'user_id' => $user->id,
        'email' => $user->email,
    ]);
    Sanctum::actingAs($user);

    $currency = Currency::query()->create(['code' => 'EUR']);
    $locale = Locale::query()->create(['code' => 'de_DE']);
    $channel = Channel::query()->create([
        'code' => 'eu',
        'name' => 'EU',
        'base_currency_id' => $currency->id,
        'default_locale_id' => $locale->id,
    ]);

    $product = Product::query()->create(['code' => 'orphan']);
    $variant = ProductVariant::query()->create(['product_id' => $product->id, 'code' => 'orphan-v', 'enabled' => true]);

    ChannelPricing::query()->create([
        'product_variant_id' => $variant->id,
        'channel_id' => $channel->id,
        'price' => 100,
    ]);

    $create = $this->postJson('/api/v1/shop/carts', ['channel_id' => $channel->id]);
    $token = $create->json('data.token');

    $add = $this->postJson("/api/v1/shop/carts/{$token}/items", [
        'product_variant_id' => $variant->id,
        'quantity' => 1,
    ]);

    $add->assertStatus(422);
});
