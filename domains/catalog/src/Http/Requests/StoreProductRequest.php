<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProductRequest extends FormRequest
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
            'code' => ['nullable', 'string', 'max:65535'],
            'enabled' => ['nullable', 'boolean'],
            'variant_selection_method' => ['nullable', 'string', 'max:65535'],
            'main_taxon_id' => ['nullable', 'integer'],
        ];
    }
}
