<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingMethod extends Model
{
    protected $table = 'shop_shipping_methods';

    protected $fillable = [
        'code',
        'name',
        'description',
        'shipping_category_id',
        'zone_id',
        'calculator',
        'configuration',
        'position',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'configuration' => 'array',
            'enabled' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ShippingCategory::class, 'shipping_category_id');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'shop_shipping_method_channel', 'shipping_method_id', 'channel_id');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'shipping_method_id');
    }
}
