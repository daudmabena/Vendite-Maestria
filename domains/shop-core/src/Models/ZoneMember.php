<?php

declare(strict_types=1);

namespace Modules\ShopCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZoneMember extends Model
{
    protected $table = 'shop_zone_members';

    protected $fillable = [
        'zone_id',
        'code',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }
}
