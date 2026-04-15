<?php

declare(strict_types=1);

namespace Modules\Promotion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromotionCoupon extends Model
{
    protected $table = 'shop_promotion_coupons';

    protected $fillable = [
        'promotion_id',
        'code',
        'usage_limit',
        'used',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'promotion_coupon_id');
    }

    public function isValid(): bool
    {
        if ($this->usage_limit !== null && $this->used >= $this->usage_limit) {
            return false;
        }

        if ($this->expires_at !== null && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function incrementUsed(): void
    {
        $this->increment('used');
    }
}
