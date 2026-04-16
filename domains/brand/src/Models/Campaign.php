<?php

declare(strict_types=1);

namespace Modules\Brand\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'brand_campaigns';

    protected $fillable = [
        'name',
        'type',
        'channel',
        'status',
        'trigger_rule',
        'audience_filter',
        'subject',
        'body',
        'sent_count',
        'opened_count',
        'converted_count',
        'scheduled_at',
        'launched_at',
    ];

    protected function casts(): array
    {
        return [
            'trigger_rule'    => 'array',
            'audience_filter' => 'array',
            'scheduled_at'    => 'datetime',
            'launched_at'     => 'datetime',
        ];
    }

    public function isLaunchable(): bool
    {
        return $this->status === 'draft' || $this->status === 'paused';
    }

    public function openRate(): float
    {
        if ($this->sent_count === 0) {
            return 0.0;
        }

        return round($this->opened_count / $this->sent_count * 100, 1);
    }

    public function conversionRate(): float
    {
        if ($this->sent_count === 0) {
            return 0.0;
        }

        return round($this->converted_count / $this->sent_count * 100, 1);
    }
}
