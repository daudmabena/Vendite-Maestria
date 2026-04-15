<?php

declare(strict_types=1);

namespace Modules\Checkout\Workflow;

use Modules\Checkout\Models\Payment;
use Modules\Checkout\Workflow\Enums\PaymentState;

/**
 * Payment gateway workflow: cart → processing → completed | failed (Sylius-aligned).
 */
final class PaymentWorkflow
{
    public function current(Payment $payment): PaymentState
    {
        $state = PaymentState::tryFromStored($payment->state);
        if ($state === null) {
            throw InvalidStateTransitionException::unknownStoredState('payment', (string) $payment->state);
        }

        return $state;
    }

    /**
     * Async gateway: cart → processing (e.g. Stripe PaymentIntent created).
     */
    public function beginProcessing(Payment $payment): void
    {
        $from = $this->current($payment);
        if ($from !== PaymentState::Cart) {
            throw InvalidStateTransitionException::forPayment($from, PaymentState::Processing);
        }

        $payment->update(['state' => PaymentState::Processing->value]);
    }

    /**
     * Manual / sync capture: cart → completed, or async capture: processing → completed.
     */
    public function complete(Payment $payment): void
    {
        $from = $this->current($payment);
        if (! in_array($from, [PaymentState::Cart, PaymentState::Processing], true)) {
            throw InvalidStateTransitionException::forPayment($from, PaymentState::Completed);
        }

        $payment->update(['state' => PaymentState::Completed->value]);
    }

    /**
     * Gateway error before completion.
     */
    public function fail(Payment $payment): void
    {
        $from = $this->current($payment);
        if (! in_array($from, [PaymentState::Cart, PaymentState::Processing], true)) {
            throw InvalidStateTransitionException::forPayment($from, PaymentState::Failed);
        }

        $payment->update(['state' => PaymentState::Failed->value]);
    }

    /**
     * Optional: void an authorized / new payment (extend guards when those paths exist).
     */
    public function cancel(Payment $payment): void
    {
        $from = $this->current($payment);
        if (! in_array($from, [PaymentState::Cart, PaymentState::New, PaymentState::Processing], true)) {
            throw InvalidStateTransitionException::forPayment($from, PaymentState::Cancelled);
        }

        $payment->update(['state' => PaymentState::Cancelled->value]);
    }
}
