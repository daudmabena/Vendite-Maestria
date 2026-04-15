<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProductAttributeValueRequest extends FormRequest
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
            'product_attribute_id' => ['nullable', 'integer'],
            'locale' => ['nullable', 'string', 'max:65535'],
            'text_value' => ['nullable', 'string', 'max:65535'],
            'integer_value' => ['nullable', 'string', 'max:65535'],
            'float_value' => ['nullable', 'string', 'max:65535'],
            'boolean_value' => ['nullable', 'string', 'max:65535'],
            'json_value' => ['nullable', 'string', 'max:65535'],
        ];
    }
}
