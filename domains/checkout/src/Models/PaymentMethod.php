<?php

declare(strict_types=1);

namespace Modules\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    public const DRIVER_MANUAL = 'manual';

    public const DRIVER_STRIPE = 'stripe';

    protected $table = 'shop_payment_methods';

    protected $fillable = [
        'code',
        'name',
        'description',
        'instructions',
        'environment',
        'driver',
        'gateway_config',
        'position',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'gateway_config' => 'array',
            'enabled' => 'boolean',
        ];
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'shop_payment_method_channel', 'payment_method_id', 'channel_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payment_method_id');
    }
}
