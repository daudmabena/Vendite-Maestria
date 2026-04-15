<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Services;

use Modules\Fulfillment\Models\ShippingMethod;

final class FlatRateShippingCalculator
{
    public static function amountMinor(ShippingMethod $method): int
    {
        if ($method->calculator !== 'flat_rate') {
            return 0;
        }

        return (int) ($method->configuration['amount'] ?? 0);
    }
}
