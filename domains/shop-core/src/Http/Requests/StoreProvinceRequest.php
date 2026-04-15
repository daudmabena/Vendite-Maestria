<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProvinceRequest extends FormRequest
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
            'country_id' => ['nullable', 'integer'],
            'code' => ['nullable', 'string', 'max:65535'],
            'name' => ['nullable', 'string', 'max:65535'],
            'abbreviation' => ['nullable', 'string', 'max:65535'],
        ];
    }
}
