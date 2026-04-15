<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateTaxRateRequest extends FormRequest
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
            'tax_category_id' => ['sometimes', 'integer'],
            'code' => ['sometimes', 'string', 'max:65535'],
            'name' => ['sometimes', 'string', 'max:65535'],
            'amount' => ['sometimes', 'numeric'],
            'included_in_price' => ['sometimes', 'boolean'],
            'calculator' => ['sometimes', 'string', 'max:65535'],
            'start_date' => ['sometimes', 'string', 'max:65535'],
            'end_date' => ['sometimes', 'string', 'max:65535'],
        ];
    }
}
