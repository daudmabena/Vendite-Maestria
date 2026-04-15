<?php

declare(strict_types=1);

namespace Modules\Promotion\Services;

use Modules\ShopCore\Models\TaxRate;

/**
 * Sylius\Component\Taxation\Calculator\DefaultCalculator — amount is a fraction (0.1 = 10%).
 */
final class DefaultTaxCalculator
{
    public function calculateExclusiveMinorUnits(int $taxableAmountMinor, TaxRate $rate): int
    {
        if ($rate->calculator !== 'default') {
            throw new \InvalidArgumentException(sprintf('Unsupported tax calculator "%s".', $rate->calculator));
        }

        return (int) round($taxableAmountMinor * (float) $rate->amount);
    }
}
