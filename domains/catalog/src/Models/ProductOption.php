<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Configurable option axis (e.g. "Size", "Color").
 */
class ProductOption extends Model
{
    protected $table = 'shop_product_options';

    protected $fillable = [
        'code',
        'name',
        'position',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(ProductOptionValue::class, 'product_option_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'shop_product_product_option', 'product_option_id', 'product_id')
            ->withPivot('position')
            ->withTimestamps();
    }
}
