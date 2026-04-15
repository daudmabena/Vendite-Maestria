<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAssociationType extends Model
{
    protected $table = 'shop_product_association_types';

    protected $fillable = [
        'code',
        'name',
    ];

    public function associations(): HasMany
    {
        return $this->hasMany(ProductAssociation::class, 'product_association_type_id');
    }
}
