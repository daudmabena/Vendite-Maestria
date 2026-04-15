<?php

declare(strict_types=1);

use Modules\Checkout\Models\Order;
use Modules\Checkout\Models\Payment;
use Modules\Checkout\Workflow\Enums\PaymentState;
use Modules\Checkout\Workflow\InvalidStateTransitionException;
use Modules\Checkout\Workflow\PaymentWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('payment workflow rejects completing from failed state', function () {
    $order = Order::query()->create(['state' => Order::STATE_CART]);
    $payment = Payment::query()->create([
        'order_id' => $order->id,
        'currency_code' => 'USD',
        'amount' => 100,
        'state' => PaymentState::Failed->value,
    ]);

    $workflow = new PaymentWorkflow;

    expect(fn () => $workflow->complete($payment))
        ->toThrow(InvalidStateTransitionException::class);
});

test('payment workflow allows cart to completed', function () {
    $order = Order::query()->create(['state' => Order::STATE_CART]);
    $payment = Payment::query()->create([
        'order_id' => $order->id,
        'currency_code' => 'USD',
        'amount' => 100,
        'state' => PaymentState::Cart->value,
    ]);

    $workflow = new PaymentWorkflow;
    $workflow->complete($payment);

    expect($payment->fresh()->state)->toBe(PaymentState::Completed->value);
});
