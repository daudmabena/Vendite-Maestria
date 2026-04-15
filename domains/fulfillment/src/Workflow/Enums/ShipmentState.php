<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Workflow\Enums;

/**
 * Persisted on {@see \Modules\Fulfillment\Models\Shipment} as <code>state</code> (string).
 */
enum ShipmentState: string
{
    case Cart = 'cart';
    case Ready = 'ready';
    case Shipped = 'shipped';
    case Cancelled = 'cancelled';

    public static function tryFromStored(?string $value): ?self
    {
        if ($value === null || $value === '') {
            return null;
        }

        return self::tryFrom($value);
    }
}
