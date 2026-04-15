<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreShippingMethodRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:65535'],
            'description' => ['nullable', 'string'],
            'shipping_category_id' => ['nullable', 'integer'],
            'zone_id' => ['nullable', 'integer'],
            'calculator' => ['nullable', 'string', 'max:65535'],
            'configuration' => ['nullable', 'array'],
            'position' => ['nullable', 'integer'],
            'enabled' => ['nullable', 'boolean'],
        ];
    }
}
