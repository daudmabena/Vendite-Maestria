<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Checkout\Models\OrderItemUnit;

class ShipmentUnit extends Model
{
    protected $table = 'shop_shipment_units';

    protected $fillable = [
        'shipment_id',
        'order_item_unit_id',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function orderItemUnit(): BelongsTo
    {
        return $this->belongsTo(OrderItemUnit::class);
    }
}
