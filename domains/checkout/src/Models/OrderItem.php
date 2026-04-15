<?php

declare(strict_types=1);

namespace Modules\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Catalog\Models\ProductVariant;

class OrderItem extends Model
{
    protected $table = 'shop_order_items';

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'product_name',
        'variant_name',
        'version',
        'quantity',
        'unit_price',
        'original_unit_price',
        'units_total',
        'adjustments_total',
        'total',
        'immutable',
    ];

    protected function casts(): array
    {
        return [
            'immutable' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (OrderItem $item): void {
            $item->order?->recalculateItemsTotal();
        });

        static::deleted(function (OrderItem $item): void {
            $item->order?->recalculateItemsTotal();
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(OrderItemUnit::class);
    }

    public function adjustments(): MorphMany
    {
        return $this->morphMany(Adjustment::class, 'adjustable');
    }

    public function recalculateUnitsTotal(): void
    {
        $unitsTotal = (int) $this->units()->get()
            ->sum(fn (OrderItemUnit $unit): int => $unit->getTotal());

        $this->forceFill(['units_total' => $unitsTotal])->save();
        $this->recalculateTotal();
    }

    public function recalculateAdjustmentsTotal(): void
    {
        $adjustmentsTotal = (int) $this->adjustments()
            ->where('neutral', false)
            ->sum('amount');

        $this->forceFill(['adjustments_total' => $adjustmentsTotal])->save();
        $this->recalculateTotal();
    }

    public function recalculateTotal(): void
    {
        $this->forceFill(['total' => max(0, $this->units_total + $this->adjustments_total)])->save();
    }
}
