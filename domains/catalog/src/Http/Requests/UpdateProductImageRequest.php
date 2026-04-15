<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProductImageRequest extends FormRequest
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
            'product_variant_id' => ['sometimes', 'integer'],
            'path' => ['sometimes', 'string', 'max:65535'],
            'position' => ['sometimes', 'integer'],
            'mime_type' => ['sometimes', 'string', 'max:65535'],
            'original_name' => ['sometimes', 'string', 'max:65535'],
        ];
    }
}
