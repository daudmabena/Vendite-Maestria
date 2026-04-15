<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTranslation extends Model
{
    protected $table = 'shop_product_translations';

    protected $fillable = [
        'product_id',
        'locale',
        'name',
        'slug',
        'description',
        'meta_keywords',
        'meta_description',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
