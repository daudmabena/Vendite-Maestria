<?php

declare(strict_types=1);

namespace Modules\ShopCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Fulfillment\Models\ShippingMethod;

class ShippingCategory extends Model
{
    protected $table = 'shop_shipping_categories';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function shippingMethods(): HasMany
    {
        return $this->hasMany(ShippingMethod::class, 'shipping_category_id');
    }
}
