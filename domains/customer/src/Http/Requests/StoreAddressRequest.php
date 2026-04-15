<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreAddressRequest extends FormRequest
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
            'customer_id' => ['nullable', 'integer'],
            'first_name' => ['nullable', 'string', 'max:65535'],
            'last_name' => ['nullable', 'string', 'max:65535'],
            'phone_number' => ['nullable', 'string', 'max:65535'],
            'company' => ['nullable', 'string', 'max:65535'],
            'country_code' => ['nullable', 'string', 'max:65535'],
            'province_code' => ['nullable', 'string', 'max:65535'],
            'province_name' => ['nullable', 'string', 'max:65535'],
            'street' => ['nullable', 'string', 'max:65535'],
            'city' => ['nullable', 'string', 'max:65535'],
            'postcode' => ['nullable', 'string', 'max:65535'],
        ];
    }
}
