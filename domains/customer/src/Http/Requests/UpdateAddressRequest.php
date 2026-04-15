<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateAddressRequest extends FormRequest
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
            'customer_id' => ['sometimes', 'integer'],
            'first_name' => ['sometimes', 'string', 'max:65535'],
            'last_name' => ['sometimes', 'string', 'max:65535'],
            'phone_number' => ['sometimes', 'string', 'max:65535'],
            'company' => ['sometimes', 'string', 'max:65535'],
            'country_code' => ['sometimes', 'string', 'max:65535'],
            'province_code' => ['sometimes', 'string', 'max:65535'],
            'province_name' => ['sometimes', 'string', 'max:65535'],
            'street' => ['sometimes', 'string', 'max:65535'],
            'city' => ['sometimes', 'string', 'max:65535'],
            'postcode' => ['sometimes', 'string', 'max:65535'],
        ];
    }
}
