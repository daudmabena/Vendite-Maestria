<?php

declare(strict_types=1);

namespace Modules\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OrderItemUnit extends Model
{
    protected $table = 'shop_order_item_units';

    protected $fillable = [
        'order_item_id',
        'adjustments_total',
    ];

    protected static function booted(): void
    {
        static::created(function (OrderItemUnit $unit): void {
            $unit->orderItem->increment('quantity');
            $unit->orderItem->recalculateUnitsTotal();
        });

        static::deleted(function (OrderItemUnit $unit): void {
            $unit->orderItem->decrement('quantity');
            $unit->orderItem->recalculateUnitsTotal();
        });
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function adjustments(): MorphMany
    {
        return $this->morphMany(Adjustment::class, 'adjustable');
    }

    public function getTotal(): int
    {
        $total = $this->orderItem->unit_price + $this->adjustments_total;

        return max(0, $total);
    }

    public function recalculateAdjustmentsTotal(): void
    {
        $adjustmentsTotal = (int) $this->adjustments()
            ->where('neutral', false)
            ->sum('amount');

        $this->forceFill(['adjustments_total' => $adjustmentsTotal])->save();
        $this->orderItem->recalculateUnitsTotal();
    }
}
