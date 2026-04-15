<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Resources;

use Modules\Checkout\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order
 */
final class OrderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Order $order */
        $order = $this->resource;

        return [
            'id' => $order->id,
            'customer_id' => $order->customer_id,
            'channel_id' => $order->channel_id,
            'number' => $order->number,
            'token_value' => $order->token_value,
            'state' => $order->state,
            'currency_code' => $order->currency_code,
            'locale_code' => $order->locale_code,
            'items_total' => $order->items_total,
            'adjustments_total' => $order->adjustments_total,
            'total' => $order->total,
            'checkout_completed_at' => $order->checkout_completed_at?->toIso8601String(),
        ];
    }
}
