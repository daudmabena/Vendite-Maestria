<?php

declare(strict_types=1);

namespace Modules\ShopCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxCategory extends Model
{
    protected $table = 'shop_tax_categories';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function rates(): HasMany
    {
        return $this->hasMany(TaxRate::class, 'tax_category_id');
    }
}
