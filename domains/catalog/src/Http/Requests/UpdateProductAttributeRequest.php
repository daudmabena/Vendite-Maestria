<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProductAttributeRequest extends FormRequest
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
            'code' => ['sometimes', 'string', 'max:65535'],
            'name' => ['sometimes', 'string', 'max:65535'],
            'type' => ['sometimes', 'string', 'max:65535'],
            'storage_type' => ['sometimes', 'string', 'max:65535'],
            'position' => ['sometimes', 'integer'],
        ];
    }
}
