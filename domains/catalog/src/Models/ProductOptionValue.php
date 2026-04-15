<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * A single choice on an axis (e.g. "M", "Red").
 */
class ProductOptionValue extends Model
{
    protected $table = 'shop_product_option_values';

    protected $fillable = [
        'product_option_id',
        'code',
        'value',
        'position',
    ];

    public function option(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'shop_product_variant_option_values',
            'product_option_value_id',
            'product_variant_id',
        )->withPivot('product_option_id');
    }
}
