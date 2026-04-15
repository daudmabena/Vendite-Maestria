<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProductImageRequest extends FormRequest
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
            'product_variant_id' => ['nullable', 'integer'],
            'path' => ['nullable', 'string', 'max:65535'],
            'position' => ['nullable', 'integer'],
            'mime_type' => ['nullable', 'string', 'max:65535'],
            'original_name' => ['nullable', 'string', 'max:65535'],
        ];
    }
}
