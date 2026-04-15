<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateCustomerRequest extends FormRequest
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
            'email' => ['sometimes', 'string', 'max:65535'],
            'email_canonical' => ['sometimes', 'string', 'max:65535'],
            'first_name' => ['sometimes', 'string', 'max:65535'],
            'last_name' => ['sometimes', 'string', 'max:65535'],
            'birthday' => ['sometimes', 'date'],
            'gender' => ['sometimes', 'string', 'max:65535'],
            'customer_group_id' => ['sometimes', 'integer'],
            'phone_number' => ['sometimes', 'string', 'max:65535'],
            'subscribed_to_newsletter' => ['sometimes', 'boolean'],
            'user_id' => ['sometimes', 'integer'],
            'default_address_id' => ['sometimes', 'integer'],
        ];
    }
}
