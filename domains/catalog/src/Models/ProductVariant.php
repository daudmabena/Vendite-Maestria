<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Checkout\Models\OrderItem;
use Modules\ShopCore\Models\Channel;
use Modules\ShopCore\Models\ShippingCategory;
use Modules\ShopCore\Models\TaxCategory;

class ProductVariant extends Model
{
    protected $table = 'shop_product_variants';

    protected $fillable = [
        'product_id',
        'code',
        'position',
        'enabled',
        'on_hand',
        'on_hold',
        'tracked',
        'tax_category_id',
        'shipping_category_id',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'tracked' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductVariantTranslation::class, 'product_variant_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_variant_id');
    }

    public function channelPricings(): HasMany
    {
        return $this->hasMany(ChannelPricing::class, 'product_variant_id');
    }

    /**
     * Images specific to this variant (product-level images live on {@see Product::productLevelImages()}).
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_variant_id')->orderBy('position');
    }

    /**
     * Selected option value per axis (one row per option on the pivot).
     */
    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductOptionValue::class,
            'shop_product_variant_option_values',
            'product_variant_id',
            'product_option_value_id',
        )->withPivot('product_option_id')->withTimestamps();
    }

    public function optionValueForOption(ProductOption $option): ?ProductOptionValue
    {
        return $this->optionValues()->wherePivot('product_option_id', $option->id)->first();
    }

    /**
     * Set which value applies for this variant on the value's option axis (replaces any previous value for that option).
     */
    public function setOptionValue(ProductOptionValue $value): void
    {
        $optionId = $value->product_option_id;
        $existingIds = $this->optionValues()
            ->wherePivot('product_option_id', $optionId)
            ->get()
            ->pluck('id');

        if ($existingIds->isNotEmpty()) {
            $this->optionValues()->detach($existingIds->all());
        }

        $this->optionValues()->attach($value->id, ['product_option_id' => $optionId]);
    }

    public function taxCategory(): BelongsTo
    {
        return $this->belongsTo(TaxCategory::class);
    }

    public function shippingCategory(): BelongsTo
    {
        return $this->belongsTo(ShippingCategory::class);
    }

    public function getChannelPricingForChannel(Channel $channel): ?ChannelPricing
    {
        return $this->channelPricings()->where('channel_id', $channel->id)->first();
    }

    public function priceForChannel(Channel $channel): ?int
    {
        return $this->getChannelPricingForChannel($channel)?->price;
    }

    /**
     * In-stock check uses on-hand quantity only (does not subtract on_hold).
     */
    public function isInStock(): bool
    {
        return ($this->on_hand ?? 0) > 0;
    }

    /**
     * Whether tracked inventory can satisfy the requested quantity (on_hand − on_hold).
     */
    public function isStockSufficient(int $quantity): bool
    {
        if (! $this->tracked) {
            return true;
        }

        $available = ($this->on_hand ?? 0) - ($this->on_hold ?? 0);

        return $quantity <= max(0, $available);
    }

    public function isStockAvailable(): bool
    {
        return $this->isStockSufficient(1);
    }

    public function getInventoryName(): ?string
    {
        return $this->product?->translate()?->name ?? $this->code;
    }

    public function translate(?string $locale = null): ?ProductVariantTranslation
    {
        $locale ??= app()->getLocale();

        return $this->translations()->where('locale', $locale)->first()
            ?? $this->translations()->first();
    }
}
