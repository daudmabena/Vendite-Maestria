<?php

declare(strict_types=1);

namespace Modules\Checkout\Workflow\Enums;

/**
 * Persisted on {@see \Modules\Checkout\Models\Order} as <code>state</code> (string).
 */
enum OrderState: string
{
    case Cart = 'cart';
    case New = 'new';
    case Cancelled = 'cancelled';
    case Fulfilled = 'fulfilled';

    public static function tryFromStored(?string $value): ?self
    {
        if ($value === null || $value === '') {
            return null;
        }

        return self::tryFrom($value);
    }
}
