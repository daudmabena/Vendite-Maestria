<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdatePaymentRequest extends FormRequest
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
            'order_id' => ['sometimes', 'integer'],
            'payment_method_id' => ['sometimes', 'integer'],
            'currency_code' => ['sometimes', 'string', 'max:65535'],
            'amount' => ['sometimes', 'integer'],
            'state' => ['sometimes', 'string', 'max:65535'],
            'details' => ['sometimes', 'array'],
        ];
    }
}
