<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdatePaymentMethodRequest extends FormRequest
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
            'instructions' => ['sometimes', 'string', 'max:65535'],
            'environment' => ['sometimes', 'string', 'max:65535'],
            'driver' => ['sometimes', 'string', 'max:65535'],
            'gateway_config' => ['sometimes', 'array'],
            'position' => ['sometimes', 'integer'],
            'enabled' => ['sometimes', 'boolean'],
        ];
    }
}
