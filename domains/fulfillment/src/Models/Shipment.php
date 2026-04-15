<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Models;

use Modules\Fulfillment\Workflow\Enums\ShipmentState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    public const STATE_CART = ShipmentState::Cart->value;

    public const STATE_READY = ShipmentState::Ready->value;

    public const STATE_SHIPPED = ShipmentState::Shipped->value;

    public const STATE_CANCELLED = ShipmentState::Cancelled->value;

    protected $table = 'shop_shipments';

    protected $fillable = [
        'order_id',
        'shipping_method_id',
        'state',
        'tracking',
        'shipped_at',
    ];

    protected function casts(): array
    {
        return [
            'shipped_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(ShipmentUnit::class);
    }
}
