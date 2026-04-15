<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreOrderRequest extends FormRequest
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
            'customer_id' => ['nullable', 'integer'],
            'channel_id' => ['nullable', 'integer'],
            'shipping_address_id' => ['nullable', 'integer'],
            'billing_address_id' => ['nullable', 'integer'],
            'promotion_coupon_id' => ['nullable', 'integer'],
            'number' => ['nullable', 'string', 'max:65535'],
            'token_value' => ['nullable', 'string', 'max:65535'],
            'currency_code' => ['nullable', 'string', 'max:65535'],
            'locale_code' => ['nullable', 'string', 'max:65535'],
            'state' => ['nullable', 'string', 'max:65535'],
            'notes' => ['nullable', 'string'],
            'checkout_completed_at' => ['nullable', 'date'],
            'items_total' => ['nullable', 'integer'],
            'adjustments_total' => ['nullable', 'integer'],
            'total' => ['nullable', 'integer'],
        ];
    }
}
