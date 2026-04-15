<?php

declare(strict_types=1);

namespace Modules\Promotion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionRule extends Model
{
    public const TYPE_MINIMUM_ORDER_AMOUNT = 'minimum_order_amount';

    public const TYPE_CONTAINS_TAXON = 'contains_taxon';

    public const TYPE_CONTAINS_PRODUCT = 'contains_product';

    protected $table = 'shop_promotion_rules';

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
