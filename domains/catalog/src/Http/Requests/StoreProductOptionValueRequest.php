<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProductOptionValueRequest extends FormRequest
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
            'product_option_id' => ['nullable', 'integer'],
            'code' => ['nullable', 'string', 'max:65535'],
            'value' => ['nullable', 'string', 'max:65535'],
            'position' => ['nullable', 'integer'],
        ];
    }
}
