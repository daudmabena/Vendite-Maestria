<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateShippingMethodRequest extends FormRequest
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
            'description' => ['sometimes', 'string'],
            'shipping_category_id' => ['sometimes', 'integer'],
            'zone_id' => ['sometimes', 'integer'],
            'calculator' => ['sometimes', 'string', 'max:65535'],
            'configuration' => ['sometimes', 'array'],
            'position' => ['sometimes', 'integer'],
            'enabled' => ['sometimes', 'boolean'],
        ];
    }
}
