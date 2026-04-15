<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreChannelPricingRequest extends FormRequest
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
            'product_variant_id' => ['required', 'integer'],
            'channel_id' => ['required', 'integer'],
            'price' => ['nullable', 'integer'],
            'original_price' => ['nullable', 'integer'],
            'minimum_price' => ['nullable', 'integer'],
            'lowest_price_before_discount' => ['nullable', 'integer'],
        ];
    }
}
