<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreCustomerRequest extends FormRequest
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
            'email' => ['nullable', 'string', 'max:65535'],
            'email_canonical' => ['nullable', 'string', 'max:65535'],
            'first_name' => ['nullable', 'string', 'max:65535'],
            'last_name' => ['nullable', 'string', 'max:65535'],
            'birthday' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:65535'],
            'customer_group_id' => ['nullable', 'integer'],
            'phone_number' => ['nullable', 'string', 'max:65535'],
            'subscribed_to_newsletter' => ['nullable', 'boolean'],
            'user_id' => ['nullable', 'integer'],
            'default_address_id' => ['nullable', 'integer'],
        ];
    }
}
