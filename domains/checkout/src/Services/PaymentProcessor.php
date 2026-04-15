<?php

declare(strict_types=1);

namespace Modules\Checkout\Services;

use Modules\Checkout\Models\Payment;
use Modules\Checkout\Models\PaymentMethod;

final class PaymentProcessor
{
    public function __construct(
        private readonly ManualPaymentDriver $manual,
        private readonly StripePaymentDriver $stripe,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function beginPayment(Payment $payment): array
    {
        $driver = $payment->method?->driver ?? PaymentMethod::DRIVER_MANUAL;

        return match ($driver) {
            PaymentMethod::DRIVER_MANUAL => $this->manual->begin($payment),
            PaymentMethod::DRIVER_STRIPE => $this->stripe->begin($payment),
            default => throw new \InvalidArgumentException(sprintf('Unknown payment driver "%s".', $driver)),
        };
    }
}
