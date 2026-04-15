<?php

declare(strict_types=1);

namespace Modules\Promotion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionAction extends Model
{
    public const TYPE_ORDER_FIXED_DISCOUNT = 'order_fixed_discount';

    public const TYPE_ORDER_PERCENTAGE_DISCOUNT = 'order_percentage_discount';

    protected $table = 'shop_promotion_actions';

    protected $fillable = [
        'promotion_id',
        'type',
        'configuration',
    ];

    protected function casts(): array
    {
        return [
            'configuration' => 'array',
        ];
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }
}
