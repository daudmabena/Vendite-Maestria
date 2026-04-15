<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProductOptionValueRequest extends FormRequest
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
            'product_option_id' => ['sometimes', 'integer'],
            'code' => ['sometimes', 'string', 'max:65535'],
            'value' => ['sometimes', 'string', 'max:65535'],
            'position' => ['sometimes', 'integer'],
        ];
    }
}
