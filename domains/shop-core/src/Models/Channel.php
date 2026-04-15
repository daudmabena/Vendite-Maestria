<?php

declare(strict_types=1);

namespace Modules\ShopCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Catalog\Models\ChannelPricing;
use Modules\Catalog\Models\Product;
use Modules\Checkout\Models\Order;
use Modules\Checkout\Models\PaymentMethod;
use Modules\Fulfillment\Models\ShippingMethod;
use Modules\Promotion\Models\Promotion;

class Channel extends Model
{
    protected $table = 'shop_channels';

    protected $fillable = [
        'code',
        'name',
        'description',
        'hostname',
        'color',
        'base_currency_id',
        'default_locale_id',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }

    public function baseCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'base_currency_id');
    }

    public function defaultLocale(): BelongsTo
    {
        return $this->belongsTo(Locale::class, 'default_locale_id');
    }

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class, 'shop_channel_currency', 'channel_id', 'currency_id');
    }

    public function locales(): BelongsToMany
    {
        return $this->belongsToMany(Locale::class, 'shop_channel_locale', 'channel_id', 'locale_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'shop_channel_product', 'channel_id', 'product_id');
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'shop_channel_country', 'channel_id', 'country_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function channelPricings(): HasMany
    {
        return $this->hasMany(ChannelPricing::class);
    }

    public function shippingMethods(): BelongsToMany
    {
        return $this->belongsToMany(ShippingMethod::class, 'shop_shipping_method_channel', 'channel_id', 'shipping_method_id');
    }

    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class, 'shop_payment_method_channel', 'channel_id', 'payment_method_id');
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'shop_promotion_channel', 'channel_id', 'promotion_id');
    }
}
