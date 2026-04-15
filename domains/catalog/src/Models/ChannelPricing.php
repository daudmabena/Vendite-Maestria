<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChannelPricing extends Model
{
    protected $table = 'shop_channel_pricings';

    protected $fillable = [
        'product_variant_id',
        'channel_id',
        'price',
        'original_price',
        'minimum_price',
        'lowest_price_before_discount',
    ];

    protected function casts(): array
    {
        return [
            'minimum_price' => 'integer',
        ];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function isPriceReduced(): bool
    {
        if ($this->original_price === null || $this->price === null) {
            return false;
        }

        return $this->original_price > $this->price;
    }
}
