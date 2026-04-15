<?php

declare(strict_types=1);

namespace Modules\ShopCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Currency extends Model
{
    protected $table = 'shop_currencies';

    protected $fillable = [
        'code',
    ];

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'shop_channel_currency', 'currency_id', 'channel_id');
    }
}
