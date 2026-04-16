<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\ShopCore\Models\Channel;

class Product extends Model
{
    public const VARIANT_SELECTION_CHOICE = 'choice';

    public const VARIANT_SELECTION_MATCH = 'match';

    protected $table = 'shop_products';

    protected $fillable = [
        'code',
        'enabled',
        'variant_selection_method',
        'main_taxon_id',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Options used to build variants (Size, Color, …).
     */
    public function options(): BelongsToMany
    {
        return $this->belongsToMany(ProductOption::class, 'shop_product_product_option', 'product_id', 'product_option_id')
            ->withPivot('position')
            ->withTimestamps();
    }

    /**
     * Non-option specs (weight, material, …), optionally per locale.
     */
    public function attributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function attributeValueFor(string $attributeCode, ?string $locale = null): ?ProductAttributeValue
    {
        $locale ??= '';

        return $this->attributeValues()
            ->where('locale', $locale)
            ->whereHas('attribute', static fn ($q) => $q->where('code', $attributeCode))
            ->first();
    }

    /**
     * All images for this product (product-level and variant-level).
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    /**
     * Gallery images not tied to a specific variant (shared product gallery).
     */
    public function productLevelImages(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->whereNull('product_variant_id')
            ->orderBy('position');
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'shop_channel_product', 'product_id', 'channel_id');
    }

    public function mainTaxon(): BelongsTo
    {
        return $this->belongsTo(Taxon::class, 'main_taxon_id');
    }

    public function taxons(): BelongsToMany
    {
        return $this->belongsToMany(Taxon::class, 'shop_product_taxon', 'product_id', 'taxon_id')
            ->withPivot('position')
            ->withTimestamps();
    }

    public function associationsWhereOwner(): HasMany
    {
        return $this->hasMany(ProductAssociation::class, 'owner_product_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function translate(?string $locale = null): ?ProductTranslation
    {
        $locale ??= app()->getLocale();

        return $this->translations()->where('locale', $locale)->first()
            ?? $this->translations()->first();
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('enabled', true);
    }
}
