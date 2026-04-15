<?php

declare(strict_types=1);

namespace Modules\Checkout\Workflow;

use Modules\Checkout\Workflow\Enums\OrderState;
use Modules\Checkout\Workflow\Enums\PaymentState;
use Modules\Fulfillment\Workflow\Enums\ShipmentState;
use InvalidArgumentException;

final class InvalidStateTransitionException extends InvalidArgumentException
{
    public static function forOrder(?OrderState $from, OrderState $to, string $detail = ''): self
    {
        $msg = sprintf(
            'Invalid order transition from %s to %s.',
            $from?->value ?? 'unknown',
            $to->value,
        );

        return new self($detail !== '' ? $msg.' '.$detail : $msg);
    }

    public static function forPayment(?PaymentState $from, PaymentState $to, string $detail = ''): self
    {
        $msg = sprintf(
            'Invalid payment transition from %s to %s.',
            $from?->value ?? 'unknown',
            $to->value,
        );

        return new self($detail !== '' ? $msg.' '.$detail : $msg);
    }

    public static function forShipment(?ShipmentState $from, ShipmentState $to, string $detail = ''): self
    {
        $msg = sprintf(
            'Invalid shipment transition from %s to %s.',
            $from?->value ?? 'unknown',
            $to->value,
        );

        return new self($detail !== '' ? $msg.' '.$detail : $msg);
    }

    public static function unknownStoredState(string $entity, string $value): self
    {
        return new self(sprintf('Unknown %s state stored in database: %s', $entity, $value));
    }
}
