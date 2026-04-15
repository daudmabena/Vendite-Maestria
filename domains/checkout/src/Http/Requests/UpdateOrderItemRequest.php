<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateOrderItemRequest extends FormRequest
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
            'product_variant_id' => ['sometimes', 'integer'],
            'product_name' => ['sometimes', 'string', 'max:65535'],
            'variant_name' => ['sometimes', 'string', 'max:65535'],
            'version' => ['sometimes', 'integer'],
            'quantity' => ['sometimes', 'integer'],
            'unit_price' => ['sometimes', 'integer'],
            'original_unit_price' => ['sometimes', 'integer'],
            'units_total' => ['sometimes', 'integer'],
            'adjustments_total' => ['sometimes', 'integer'],
            'total' => ['sometimes', 'integer'],
            'immutable' => ['sometimes', 'boolean'],
        ];
    }
}
