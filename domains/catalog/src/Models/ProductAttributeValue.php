<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Attribute value on a product (optionally per-locale).
 */
class ProductAttributeValue extends Model
{
    protected $table = 'shop_product_attribute_values';

    protected $fillable = [
        'product_id',
        'product_attribute_id',
        'locale',
        'text_value',
        'integer_value',
        'float_value',
        'boolean_value',
        'json_value',
    ];

    protected function casts(): array
    {
        return [
            'boolean_value' => 'boolean',
            'json_value' => 'array',
            'float_value' => 'float',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }

    /**
     * Resolved scalar/array for display based on attribute storage_type.
     */
    public function getResolvedValue(): string|int|float|bool|array|null
    {
        $storage = $this->attribute?->storage_type ?? ProductAttribute::STORAGE_TEXT;

        return match ($storage) {
            ProductAttribute::STORAGE_INTEGER => $this->integer_value,
            ProductAttribute::STORAGE_FLOAT => $this->float_value,
            ProductAttribute::STORAGE_BOOLEAN => $this->boolean_value,
            ProductAttribute::STORAGE_JSON => $this->json_value,
            default => $this->text_value,
        };
    }
}
