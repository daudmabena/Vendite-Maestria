<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProductVariantRequest extends FormRequest
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
            'product_id' => ['sometimes', 'integer'],
            'code' => ['sometimes', 'string', 'max:65535'],
            'position' => ['sometimes', 'integer'],
            'enabled' => ['sometimes', 'boolean'],
            'on_hand' => ['sometimes', 'integer'],
            'on_hold' => ['sometimes', 'integer'],
            'tracked' => ['sometimes', 'boolean'],
            'tax_category_id' => ['sometimes', 'integer'],
            'shipping_category_id' => ['sometimes', 'integer'],
        ];
    }
}
