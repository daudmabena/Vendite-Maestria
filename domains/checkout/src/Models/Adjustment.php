<?php

declare(strict_types=1);

namespace Modules\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Adjustment extends Model
{
    public const ORDER_ITEM_PROMOTION_ADJUSTMENT = 'order_item_promotion';
    public const ORDER_PROMOTION_ADJUSTMENT = 'order_promotion';
    public const ORDER_SHIPPING_PROMOTION_ADJUSTMENT = 'order_shipping_promotion';
    public const ORDER_UNIT_PROMOTION_ADJUSTMENT = 'order_unit_promotion';
    public const SHIPPING_ADJUSTMENT = 'shipping';
    public const TAX_ADJUSTMENT = 'tax';

    protected $table = 'shop_adjustments';

    protected $fillable = [
        'type',
        'label',
        'amount',
        'neutral',
        'locked',
        'origin_code',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
            'neutral' => 'boolean',
            'locked' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (Adjustment $adjustment): void {
            $adjustment->recalculateAdjustable();
        });

        static::deleted(function (Adjustment $adjustment): void {
            $adjustment->recalculateAdjustable();
        });
    }

    public function adjustable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isNeutral(): bool
    {
        return $this->neutral;
    }

    public function isLocked(): bool
    {
        return $this->locked;
    }

    public function isCharge(): bool
    {
        return $this->amount < 0;
    }

    public function isCredit(): bool
    {
        return $this->amount > 0;
    }

    private function recalculateAdjustable(): void
    {
        $adjustable = $this->adjustable;
        if ($adjustable !== null && method_exists($adjustable, 'recalculateAdjustmentsTotal')) {
            $adjustable->recalculateAdjustmentsTotal();
        }
    }
}
