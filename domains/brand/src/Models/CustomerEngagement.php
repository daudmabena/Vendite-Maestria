<?php

declare(strict_types=1);

namespace Modules\Brand\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerEngagement extends Model
{
    protected $table = 'brand_customer_engagements';

    protected $fillable = [
        'customer_id',
        'familiarity_score',
        'total_touchpoints',
        'trust_tier',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'last_seen_at' => 'datetime',
            'familiarity_score' => 'integer',
            'total_touchpoints' => 'integer',
        ];
    }

    public function isLapsing(): bool
    {
        return $this->last_seen_at !== null
            && $this->last_seen_at->diffInDays(now()) >= 30;
    }
}
