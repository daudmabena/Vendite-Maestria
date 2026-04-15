<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Product spec definition (material, weight, …) — Sylius ProductAttribute.
 */
class ProductAttribute extends Model
{
    public const TYPE_TEXT = 'text';

    public const TYPE_INTEGER = 'integer';

    public const TYPE_FLOAT = 'float';

    public const TYPE_BOOLEAN = 'boolean';

    public const TYPE_SELECT = 'select';

    public const STORAGE_TEXT = 'text';

    public const STORAGE_INTEGER = 'integer';

    public const STORAGE_FLOAT = 'float';

    public const STORAGE_BOOLEAN = 'boolean';

    public const STORAGE_JSON = 'json';

    protected $table = 'shop_product_attributes';

    protected $fillable = [
        'code',
        'name',
        'type',
        'storage_type',
        'position',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class, 'product_attribute_id');
    }
}
