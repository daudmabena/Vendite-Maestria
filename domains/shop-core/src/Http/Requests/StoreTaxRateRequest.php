<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreTaxRateRequest extends FormRequest
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
            'tax_category_id' => ['nullable', 'integer'],
            'code' => ['nullable', 'string', 'max:65535'],
            'name' => ['nullable', 'string', 'max:65535'],
            'amount' => ['nullable', 'numeric'],
            'included_in_price' => ['nullable', 'boolean'],
            'calculator' => ['nullable', 'string', 'max:65535'],
            'start_date' => ['nullable', 'string', 'max:65535'],
            'end_date' => ['nullable', 'string', 'max:65535'],
        ];
    }
}
