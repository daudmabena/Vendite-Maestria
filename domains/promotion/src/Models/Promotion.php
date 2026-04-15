<?php

declare(strict_types=1);

namespace Modules\Promotion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\ShopCore\Models\Channel;

class Promotion extends Model
{
    protected $table = 'shop_promotions';

    protected $fillable = [
        'code',
        'name',
        'description',
        'exclusive',
        'priority',
        'usage_limit',
        'used',
        'starts_at',
        'ends_at',
        'coupon_based',
        'applies_to_discounted',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'exclusive' => 'boolean',
            'coupon_based' => 'boolean',
            'applies_to_discounted' => 'boolean',
            'enabled' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'shop_promotion_channel', 'promotion_id', 'channel_id');
    }

    public function rules(): HasMany
    {
        return $this->hasMany(PromotionRule::class);
    }

    public function actions(): HasMany
    {
        return $this->hasMany(PromotionAction::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(PromotionCoupon::class);
    }

    public function incrementUsed(): void
    {
        $this->increment('used');
    }
}
