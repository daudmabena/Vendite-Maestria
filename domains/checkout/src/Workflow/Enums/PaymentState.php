<?php

declare(strict_types=1);

namespace Modules\Checkout\Workflow\Enums;

/**
 * Persisted on {@see \Modules\Checkout\Models\Payment} as <code>state</code> (string).
 */
enum PaymentState: string
{
    case Cart = 'cart';
    case New = 'new';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';
    case Cancelled = 'cancelled';
    case Authorized = 'authorized';
    case Refunded = 'refunded';
    case Unknown = 'unknown';

    public static function tryFromStored(?string $value): ?self
    {
        if ($value === null || $value === '') {
            return null;
        }

        return self::tryFrom($value);
    }
}
