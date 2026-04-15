<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProductRequest extends FormRequest
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
            'enabled' => ['sometimes', 'boolean'],
            'variant_selection_method' => ['sometimes', 'string', 'max:65535'],
            'main_taxon_id' => ['sometimes', 'integer'],
        ];
    }
}
