<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreTaxonRequest extends FormRequest
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
            'parent_id' => ['nullable', 'integer'],
            'position' => ['nullable', 'integer'],
            'enabled' => ['nullable', 'boolean'],
        ];
    }
}
