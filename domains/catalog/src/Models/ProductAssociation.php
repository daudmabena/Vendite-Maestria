<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductAssociation extends Model
{
    protected $table = 'shop_product_associations';

    protected $fillable = [
        'owner_product_id',
        'product_association_type_id',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'owner_product_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductAssociationType::class, 'product_association_type_id');
    }

    public function associatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'shop_product_association_product',
            'product_association_id',
            'product_id',
        );
    }
}
