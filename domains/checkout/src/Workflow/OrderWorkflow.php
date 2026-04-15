<?php

declare(strict_types=1);

namespace Modules\Checkout\Workflow;

use Modules\Checkout\Models\Order;
use Modules\Checkout\Workflow\Enums\OrderState;

/**
 * Order lifecycle (Sylius-style): cart → placed (new) → fulfilled / cancelled.
 */
final class OrderWorkflow
{
    public function current(Order $order): OrderState
    {
        $state = OrderState::tryFromStored($order->state);
        if ($state === null) {
            throw InvalidStateTransitionException::unknownStoredState('order', (string) $order->state);
        }

        return $state;
    }

    public function assertCart(Order $order): void
    {
        $from = $this->current($order);
        if ($from !== OrderState::Cart) {
            throw InvalidStateTransitionException::forOrder($from, OrderState::Cart, 'Expected cart order.');
        }
    }

    /**
     * Checkout placed successfully (payment completed synchronously).
     */
    public function placeOrder(Order $order): void
    {
        $from = $this->current($order);
        if ($from !== OrderState::Cart) {
            throw InvalidStateTransitionException::forOrder($from, OrderState::New);
        }

        $order->update(['state' => OrderState::New->value]);
    }

    /**
     * Admin / ops: cancel a placed order that is not yet fulfilled.
     */
    public function cancel(Order $order): void
    {
        $from = $this->current($order);
        if ($from !== OrderState::New) {
            throw InvalidStateTransitionException::forOrder($from, OrderState::Cancelled);
        }

        $order->update(['state' => OrderState::Cancelled->value]);
    }

    /**
     * Fulfillment completed.
     */
    public function fulfill(Order $order): void
    {
        $from = $this->current($order);
        if ($from !== OrderState::New) {
            throw InvalidStateTransitionException::forOrder($from, OrderState::Fulfilled);
        }

        $order->update(['state' => OrderState::Fulfilled->value]);
    }
}
