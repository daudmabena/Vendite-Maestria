<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateChannelPricingRequest extends FormRequest
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
            'product_variant_id' => ['sometimes', 'integer'],
            'channel_id' => ['sometimes', 'integer'],
            'price' => ['sometimes', 'nullable', 'integer'],
            'original_price' => ['sometimes', 'nullable', 'integer'],
            'minimum_price' => ['sometimes', 'nullable', 'integer'],
            'lowest_price_before_discount' => ['sometimes', 'nullable', 'integer'],
        ];
    }
}
