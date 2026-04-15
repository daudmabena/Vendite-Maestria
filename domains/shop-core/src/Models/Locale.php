<?php

declare(strict_types=1);

namespace Modules\ShopCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Locale extends Model
{
    protected $table = 'shop_locales';

    protected $fillable = [
        'code',
    ];

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'shop_channel_locale', 'locale_id', 'channel_id');
    }
}
