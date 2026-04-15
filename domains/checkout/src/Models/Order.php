<?php

declare(strict_types=1);

namespace Modules\Checkout\Models;

use Modules\Checkout\Workflow\Enums\OrderState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;
use Modules\Fulfillment\Models\Shipment;
use Modules\Promotion\Models\PromotionCoupon;
use Modules\ShopCore\Models\Channel;

class Order extends Model
{
    public const STATE_CART = OrderState::Cart->value;

    public const STATE_NEW = OrderState::New->value;

    public const STATE_CANCELLED = OrderState::Cancelled->value;

    public const STATE_FULFILLED = OrderState::Fulfilled->value;

    protected $table = 'shop_orders';

    protected $fillable = [
        'customer_id',
        'channel_id',
        'shipping_address_id',
        'billing_address_id',
        'promotion_coupon_id',
        'number',
        'token_value',
        'currency_code',
        'locale_code',
        'state',
        'notes',
        'checkout_completed_at',
        'items_total',
        'adjustments_total',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'checkout_completed_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function promotionCoupon(): BelongsTo
    {
        return $this->belongsTo(PromotionCoupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function adjustments(): MorphMany
    {
        return $this->morphMany(Adjustment::class, 'adjustable');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function completeCheckout(): void
    {
        $this->update(['checkout_completed_at' => now()]);
    }

    public function isCheckoutCompleted(): bool
    {
        return $this->checkout_completed_at !== null;
    }

    public function getTotalQuantity(): int
    {
        return (int) $this->items()->sum('quantity');
    }

    public function isEmpty(): bool
    {
        return ! $this->items()->exists();
    }

    public function canBeProcessed(): bool
    {
        return $this->state === self::STATE_CART;
    }

    public function recalculateItemsTotal(): void
    {
        $itemsTotal = (int) $this->items()->sum('total');
        $this->forceFill(['items_total' => $itemsTotal])->save();
        $this->recalculateTotal();
    }

    public function recalculateAdjustmentsTotal(): void
    {
        $total = (int) $this->adjustments()
            ->where('neutral', false)
            ->sum('amount');

        $this->forceFill(['adjustments_total' => $total])->save();
        $this->recalculateTotal();
    }

    public function recalculateTotal(): void
    {
        $total = $this->items_total + $this->adjustments_total;
        $this->forceFill(['total' => max(0, $total)])->save();
    }
}
