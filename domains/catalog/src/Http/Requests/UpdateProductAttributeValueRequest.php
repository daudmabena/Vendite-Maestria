<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProductAttributeValueRequest extends FormRequest
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
            'product_attribute_id' => ['sometimes', 'integer'],
            'locale' => ['sometimes', 'string', 'max:65535'],
            'text_value' => ['sometimes', 'string', 'max:65535'],
            'integer_value' => ['sometimes', 'string', 'max:65535'],
            'float_value' => ['sometimes', 'string', 'max:65535'],
            'boolean_value' => ['sometimes', 'string', 'max:65535'],
            'json_value' => ['sometimes', 'string', 'max:65535'],
        ];
    }
}
