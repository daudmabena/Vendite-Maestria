<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateChannelRequest extends FormRequest
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
            'hostname' => ['sometimes', 'string', 'max:65535'],
            'color' => ['sometimes', 'string', 'max:65535'],
            'base_currency_id' => ['sometimes', 'integer'],
            'default_locale_id' => ['sometimes', 'integer'],
            'enabled' => ['sometimes', 'boolean'],
        ];
    }
}
