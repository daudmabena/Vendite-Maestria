<?php

declare(strict_types=1);

namespace Modules\ShopCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Province extends Model
{
    protected $table = 'shop_provinces';

    protected $fillable = [
        'country_id',
        'code',
        'name',
        'abbreviation',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
