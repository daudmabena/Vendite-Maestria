<?php

declare(strict_types=1);

namespace Modules\Checkout\Services;

use Modules\Checkout\Models\Payment;
use Modules\Checkout\Workflow\PaymentWorkflow;
use Illuminate\Support\Facades\Http;

/**
 * Stripe PaymentIntents via Laravel HTTP client (no Payum). Add STRIPE_SECRET to .env.
 */
final class StripePaymentDriver
{
    public function __construct(
        private readonly PaymentWorkflow $paymentWorkflow,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function begin(Payment $payment): array
    {
        $secret = config('services.stripe.secret');
        if ($secret === null || $secret === '') {
            return [
                'status' => 'configuration_required',
                'message' => 'Set STRIPE_SECRET in .env to create PaymentIntents.',
            ];
        }

        $response = Http::withToken($secret)
            ->asForm()
            ->post('https://api.stripe.com/v1/payment_intents', [
                'amount' => $payment->amount,
                'currency' => strtolower($payment->currency_code),
                'metadata[order_id]' => (string) $payment->order_id,
                'automatic_payment_methods[enabled]' => 'true',
            ]);

        if (! $response->successful()) {
            $payment->update([
                'details' => array_merge($payment->details ?? [], [
                    'gateway' => 'stripe',
                    'error' => $response->json('error.message', $response->body()),
                ]),
            ]);
            $this->paymentWorkflow->fail($payment->fresh());

            return ['status' => 'failed', 'error' => $response->json('error')];
        }

        $data = $response->json();
        $payment->update([
            'details' => array_merge($payment->details ?? [], [
                'gateway' => 'stripe',
                'payment_intent_id' => $data['id'] ?? null,
                'client_secret' => $data['client_secret'] ?? null,
            ]),
        ]);
        $this->paymentWorkflow->beginProcessing($payment->fresh());

        return [
            'status' => 'processing',
            'client_secret' => $data['client_secret'] ?? null,
            'payment_intent_id' => $data['id'] ?? null,
        ];
    }
}
