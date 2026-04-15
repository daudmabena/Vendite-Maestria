<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Resources;

use Modules\Checkout\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order
 */
final class CartResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Order $order */
        $order = $this->resource;

        return [
            'token' => $order->token_value,
            'state' => $order->state,
            'number' => $order->number,
            'currency_code' => $order->currency_code,
            'locale_code' => $order->locale_code,
            'channel_id' => $order->channel_id,
            'items_total' => $order->items_total,
            'adjustments_total' => $order->adjustments_total,
            'total' => $order->total,
            'promotion_coupon_id' => $order->promotion_coupon_id,
            'items' => $order->items->map(static function ($item): array {
                return [
                    'id' => $item->id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product_name,
                    'variant_name' => $item->variant_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total' => $item->total,
                ];
            }),
            'adjustments' => $order->adjustments->map(static function ($adj): array {
                return [
                    'type' => $adj->type,
                    'label' => $adj->label,
                    'amount' => $adj->amount,
                    'neutral' => $adj->neutral,
                ];
            }),
        ];
    }
}
