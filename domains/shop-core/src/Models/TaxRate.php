<?php

declare(strict_types=1);

namespace Modules\ShopCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRate extends Model
{
    protected $table = 'shop_tax_rates';

    protected $fillable = [
        'tax_category_id',
        'code',
        'name',
        'amount',
        'included_in_price',
        'calculator',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'included_in_price' => 'boolean',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TaxCategory::class, 'tax_category_id');
    }

    public function getAmountAsPercentage(): float
    {
        return $this->amount * 100;
    }
}
