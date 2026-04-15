<?php

declare(strict_types=1);

namespace Modules\Checkout\Services;

use Modules\Checkout\Models\Payment;
use Modules\Checkout\Workflow\PaymentWorkflow;

/**
 * Cash on delivery / manual capture — no Payum; marks payment completed for fulfillment flows.
 */
final class ManualPaymentDriver
{
    public function __construct(
        private readonly PaymentWorkflow $paymentWorkflow,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function begin(Payment $payment): array
    {
        $payment->update([
            'details' => array_merge($payment->details ?? [], [
                'gateway' => 'manual',
                'completed_at' => now()->toIso8601String(),
            ]),
        ]);

        $this->paymentWorkflow->complete($payment->fresh());

        return ['status' => 'completed'];
    }
}
