<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StorePaymentRequest extends FormRequest
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
            'order_id' => ['nullable', 'integer'],
            'payment_method_id' => ['nullable', 'integer'],
            'currency_code' => ['nullable', 'string', 'max:65535'],
            'amount' => ['nullable', 'integer'],
            'state' => ['nullable', 'string', 'max:65535'],
            'details' => ['nullable', 'array'],
        ];
    }
}
