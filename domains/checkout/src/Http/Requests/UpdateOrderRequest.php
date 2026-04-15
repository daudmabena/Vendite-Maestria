<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['sometimes', 'integer'],
            'channel_id' => ['sometimes', 'integer'],
            'shipping_address_id' => ['sometimes', 'integer'],
            'billing_address_id' => ['sometimes', 'integer'],
            'promotion_coupon_id' => ['sometimes', 'integer'],
            'number' => ['sometimes', 'string', 'max:65535'],
            'token_value' => ['sometimes', 'string', 'max:65535'],
            'currency_code' => ['sometimes', 'string', 'max:65535'],
            'locale_code' => ['sometimes', 'string', 'max:65535'],
            'state' => ['sometimes', 'string', 'max:65535'],
            'notes' => ['sometimes', 'string'],
            'checkout_completed_at' => ['sometimes', 'date'],
            'items_total' => ['sometimes', 'integer'],
            'adjustments_total' => ['sometimes', 'integer'],
            'total' => ['sometimes', 'integer'],
        ];
    }
}
