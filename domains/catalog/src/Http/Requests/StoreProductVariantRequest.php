<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProductVariantRequest extends FormRequest
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
            'product_id' => ['nullable', 'integer'],
            'code' => ['nullable', 'string', 'max:65535'],
            'position' => ['nullable', 'integer'],
            'enabled' => ['nullable', 'boolean'],
            'on_hand' => ['nullable', 'integer'],
            'on_hold' => ['nullable', 'integer'],
            'tracked' => ['nullable', 'boolean'],
            'tax_category_id' => ['nullable', 'integer'],
            'shipping_category_id' => ['nullable', 'integer'],
        ];
    }
}
