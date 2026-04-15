<?php

declare(strict_types=1);

namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_SPAM = 'spam';

    protected $table = 'shop_contact_messages';

    protected $fillable = [
        'email',
        'name',
        'subject',
        'message',
        'status',
        'resolved_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
            'meta' => 'array',
        ];
    }
}

