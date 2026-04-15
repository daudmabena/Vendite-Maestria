<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StorePaymentMethodRequest extends FormRequest
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
            'instructions' => ['nullable', 'string', 'max:65535'],
            'environment' => ['nullable', 'string', 'max:65535'],
            'driver' => ['nullable', 'string', 'max:65535'],
            'gateway_config' => ['nullable', 'array'],
            'position' => ['nullable', 'integer'],
            'enabled' => ['nullable', 'boolean'],
        ];
    }
}
