<?php

declare(strict_types=1);

namespace Modules\Checkout\Services;

use Modules\ShopCore\Models\Channel;
use Modules\Checkout\Models\Order;
use Modules\Checkout\Models\OrderItem;
use Modules\Checkout\Models\OrderItemUnit;
use Modules\Checkout\Models\Payment;
use Modules\Checkout\Models\PaymentMethod;
use Modules\Catalog\Models\ProductVariant;
use Modules\Promotion\Models\PromotionCoupon;
use Modules\Fulfillment\Models\Shipment;
use Modules\Fulfillment\Models\ShippingMethod;
use Modules\Checkout\Notifications\OrderConfirmationNotification;
use Modules\Checkout\Services\OrderIdentifierGenerator;
use Modules\Checkout\Services\PaymentProcessor;
use Modules\Checkout\Workflow\Enums\OrderState;
use Modules\Checkout\Workflow\Enums\PaymentState;
use Modules\Checkout\Workflow\InvalidStateTransitionException;
use Modules\Checkout\Workflow\OrderWorkflow;
use Modules\Fulfillment\Workflow\ShipmentWorkflow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class CartService
{
    public function __construct(
        private readonly CartTotalsRefresher $totalsRefresher,
        private readonly PaymentProcessor $paymentProcessor,
        private readonly OrderWorkflow $orderWorkflow,
        private readonly ShipmentWorkflow $shipmentWorkflow,
        private readonly OrderIdentifierGenerator $orderIdentifierGenerator,
    ) {}

    public function createCart(int $channelId, ?int $customerId = null): Order
    {
        $channel = Channel::query()->findOrFail($channelId);

        $currencyCode = $channel->baseCurrency?->code;
        if ($currencyCode === null || $currencyCode === '') {
            throw ValidationException::withMessages([
                'channel_id' => ['Channel must have a base currency for checkout.'],
            ]);
        }

        $order = Order::query()->create([
            'customer_id' => $customerId,
            'channel_id' => $channel->id,
            'currency_code' => $currencyCode,
            'locale_code' => $channel->defaultLocale?->code,
            'state' => OrderState::Cart->value,
            'token_value' => $this->orderIdentifierGenerator->generateToken(),
        ]);

        return $order->fresh();
    }

    public function addItem(Order $order, int $variantId, int $quantity): OrderItem
    {
        $this->assertOpenCart($order);

        if ($quantity < 1) {
            throw ValidationException::withMessages(['quantity' => ['Quantity must be at least 1.']]);
        }

        $variant = ProductVariant::query()->with(['product'])->findOrFail($variantId);

        if (! $variant->enabled) {
            throw ValidationException::withMessages(['product_variant_id' => ['Variant is not available.']]);
        }

        $channel = $order->channel ?? Channel::query()->find($order->channel_id);
        if ($channel === null) {
            throw ValidationException::withMessages(['order' => ['Cart has no channel.']]);
        }

        if (! $channel->products()->whereKey($variant->product_id)->exists()) {
            throw ValidationException::withMessages(['product_variant_id' => ['Product is not available in this channel.']]);
        }

        $unitPrice = $variant->priceForChannel($channel);
        if ($unitPrice === null) {
            throw ValidationException::withMessages(['product_variant_id' => ['No price for this channel.']]);
        }

        $item = DB::transaction(function () use ($order, $variant, $quantity, $unitPrice, $channel): OrderItem {
            $existing = OrderItem::query()
                ->where('order_id', $order->id)
                ->where('product_variant_id', $variant->id)
                ->first();

            if ($existing !== null) {
                $newQty = $existing->quantity + $quantity;
                if ($variant->tracked && ! $variant->isStockSufficient($newQty)) {
                    throw ValidationException::withMessages(['quantity' => ['Insufficient stock.']]);
                }

                for ($i = 0; $i < $quantity; $i++) {
                    OrderItemUnit::query()->create(['order_item_id' => $existing->id]);
                }

                return $existing->fresh();
            }

            if ($variant->tracked && ! $variant->isStockSufficient($quantity)) {
                throw ValidationException::withMessages(['quantity' => ['Insufficient stock.']]);
            }

            $productName = $variant->product?->translate()?->name ?? $variant->product?->code;
            $variantName = $variant->translate()?->name ?? $variant->code;

            $line = OrderItem::query()->create([
                'order_id' => $order->id,
                'product_variant_id' => $variant->id,
                'product_name' => $productName,
                'variant_name' => $variantName,
                'unit_price' => $unitPrice,
                'original_unit_price' => $variant->getChannelPricingForChannel($channel)?->original_price,
            ]);

            for ($i = 0; $i < $quantity; $i++) {
                OrderItemUnit::query()->create(['order_item_id' => $line->id]);
            }

            return $line->fresh();
        });

        $this->totalsRefresher->refresh($order->fresh());

        return $item;
    }

    public function setItemQuantity(Order $order, int $orderItemId, int $quantity): void
    {
        $this->assertOpenCart($order);

        $item = OrderItem::query()
            ->where('order_id', $order->id)
            ->whereKey($orderItemId)
            ->firstOrFail();

        $variant = $item->variant;
        if ($variant === null) {
            throw ValidationException::withMessages(['item' => ['Line has no variant.']]);
        }

        if ($quantity < 1) {
            $this->removeItem($order, $orderItemId);

            return;
        }

        if ($variant->tracked && ! $variant->isStockSufficient($quantity)) {
            throw ValidationException::withMessages(['quantity' => ['Insufficient stock.']]);
        }

        $delta = $quantity - $item->quantity;
        if ($delta > 0) {
            for ($i = 0; $i < $delta; $i++) {
                OrderItemUnit::query()->create(['order_item_id' => $item->id]);
            }
        } elseif ($delta < 0) {
            $toRemove = $item->units()->orderByDesc('id')->limit(abs($delta))->pluck('id');
            OrderItemUnit::query()->whereIn('id', $toRemove)->delete();
        }

        $this->totalsRefresher->refresh($order->fresh());
    }

    public function removeItem(Order $order, int $orderItemId): void
    {
        $this->assertOpenCart($order);

        $item = OrderItem::query()
            ->where('order_id', $order->id)
            ->whereKey($orderItemId)
            ->first();

        if ($item !== null) {
            $item->delete();
        }

        $this->totalsRefresher->refresh($order->fresh());
    }

    public function applyCoupon(Order $order, string $code): void
    {
        $this->assertOpenCart($order);

        $normalized = trim($code);
        if ($normalized === '') {
            throw ValidationException::withMessages(['code' => ['Coupon code is required.']]);
        }

        $coupon = PromotionCoupon::query()->where('code', $normalized)->first();
        if ($coupon === null || ! $coupon->isValid()) {
            throw ValidationException::withMessages(['code' => ['Invalid or expired coupon.']]);
        }

        $order->update(['promotion_coupon_id' => $coupon->id]);

        $this->totalsRefresher->refresh($order->fresh());
    }

    public function removeCoupon(Order $order): void
    {
        $this->assertOpenCart($order);

        $order->update(['promotion_coupon_id' => null]);

        $this->totalsRefresher->refresh($order->fresh());
    }

    public function selectShippingMethod(Order $order, int $shippingMethodId): void
    {
        $this->assertOpenCart($order);

        $channel = $order->channel ?? Channel::query()->find($order->channel_id);
        if ($channel === null) {
            throw ValidationException::withMessages(['order' => ['Cart has no channel.']]);
        }

        $method = ShippingMethod::query()->findOrFail($shippingMethodId);
        if (! $channel->shippingMethods()->whereKey($method->id)->exists()) {
            throw ValidationException::withMessages(['shipping_method_id' => ['Shipping method is not available for this channel.']]);
        }

        DB::transaction(function () use ($order, $method): void {
            $shipment = $order->shipments()->orderBy('id')->first();
            if ($shipment === null) {
                $shipment = $order->shipments()->create([
                    'shipping_method_id' => $method->id,
                    'state' => Shipment::STATE_CART,
                ]);
            } else {
                $shipment->update([
                    'shipping_method_id' => $method->id,
                ]);
            }

            $this->shipmentWorkflow->markCart($shipment->fresh());
        });

        $this->totalsRefresher->refresh($order->fresh());
    }

    /**
     * @return array{payment: Payment, processor_result: array<string, mixed>}
     */
    public function checkout(Order $order, int $paymentMethodId): array
    {
        $this->assertOpenCart($order);

        if ($order->isEmpty()) {
            throw ValidationException::withMessages(['order' => ['Cart is empty.']]);
        }

        $channel = $order->channel ?? Channel::query()->find($order->channel_id);
        if ($channel === null) {
            throw ValidationException::withMessages(['order' => ['Cart has no channel.']]);
        }

        $method = PaymentMethod::query()->findOrFail($paymentMethodId);
        if (! $method->enabled || ! $channel->paymentMethods()->whereKey($method->id)->exists()) {
            throw ValidationException::withMessages(['payment_method_id' => ['Payment method is not available for this channel.']]);
        }

        $this->totalsRefresher->refresh($order->fresh());

        $order->refresh();

        if ($order->total < 0) {
            throw ValidationException::withMessages(['order' => ['Order total is invalid.']]);
        }

        return DB::transaction(function () use ($order, $method) {
            Payment::query()
                ->where('order_id', $order->id)
                ->whereIn('state', [Payment::STATE_CART, Payment::STATE_NEW, Payment::STATE_PROCESSING])
                ->delete();

            $payment = Payment::query()->create([
                'order_id' => $order->id,
                'payment_method_id' => $method->id,
                'currency_code' => $order->currency_code ?? 'USD',
                'amount' => $order->total,
                'state' => PaymentState::Cart->value,
            ]);

            $result = $this->paymentProcessor->beginPayment($payment);

            $payment->refresh();

            if ($payment->state === PaymentState::Completed->value) {
                $placed = $order->fresh();
                $number = $placed->number;
                if ($number === null || $number === '') {
                    $number = $this->orderIdentifierGenerator->generateNumber($placed);
                }

                $placed->update(['number' => $number]);
                $this->orderWorkflow->placeOrder($placed->fresh());
                $final = $placed->fresh();
                $this->reserveInventoryOnCheckout($final);
                $final->completeCheckout();
                $final->promotionCoupon?->incrementUsed();
                $final->customer?->user?->notify(new OrderConfirmationNotification($final));
            }

            return ['payment' => $payment, 'processor_result' => $result];
        });
    }

    private function assertOpenCart(Order $order): void
    {
        try {
            $this->orderWorkflow->assertCart($order);
        } catch (InvalidStateTransitionException) {
            throw ValidationException::withMessages(['order' => ['Order is not an open cart.']]);
        }

        if ($order->isCheckoutCompleted()) {
            throw ValidationException::withMessages(['order' => ['Checkout already completed.']]);
        }
    }

    private function reserveInventoryOnCheckout(Order $order): void
    {
        $order->loadMissing('items.variant');

        /** @var Collection<int, OrderItem> $items */
        $items = $order->items;
        foreach ($items as $item) {
            $variant = $item->variant;
            if ($variant === null || ! $variant->tracked) {
                continue;
            }

            $quantity = max(0, (int) $item->quantity);
            if ($quantity === 0) {
                continue;
            }

            $available = max(0, (int) $variant->on_hand - (int) $variant->on_hold);
            if ($available < $quantity) {
                throw ValidationException::withMessages([
                    'order' => ['Insufficient stock at checkout for variant '.$variant->code.'.'],
                ]);
            }

            $variant->increment('on_hold', $quantity);
        }
    }
}
