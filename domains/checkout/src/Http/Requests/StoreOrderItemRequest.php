<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreOrderItemRequest extends FormRequest
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
            'product_variant_id' => ['nullable', 'integer'],
            'product_name' => ['nullable', 'string', 'max:65535'],
            'variant_name' => ['nullable', 'string', 'max:65535'],
            'version' => ['nullable', 'integer'],
            'quantity' => ['nullable', 'integer'],
            'unit_price' => ['nullable', 'integer'],
            'original_unit_price' => ['nullable', 'integer'],
            'units_total' => ['nullable', 'integer'],
            'adjustments_total' => ['nullable', 'integer'],
            'total' => ['nullable', 'integer'],
            'immutable' => ['nullable', 'boolean'],
        ];
    }
}
