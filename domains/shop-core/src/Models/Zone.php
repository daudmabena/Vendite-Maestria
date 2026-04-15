<?php

declare(strict_types=1);

namespace Modules\ShopCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    public const TYPE_COUNTRY = 'country';

    public const TYPE_PROVINCE = 'province';

    public const TYPE_ZONE = 'zone';

    public const SCOPE_ALL = 'all';

    protected $table = 'shop_zones';

    protected $fillable = [
        'code',
        'name',
        'type',
        'scope',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'priority' => 'integer',
        ];
    }

    public function members(): HasMany
    {
        return $this->hasMany(ZoneMember::class);
    }

    /**
     * @return list<string>
     */
    public static function types(): array
    {
        return [self::TYPE_COUNTRY, self::TYPE_PROVINCE, self::TYPE_ZONE];
    }
}
