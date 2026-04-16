<?php

declare(strict_types=1);

namespace Modules\Brand\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Touchpoint extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'brand_touchpoints';

    protected $fillable = [
        'customer_id',
        'type',
        'source',
        'entity_type',
        'entity_id',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
