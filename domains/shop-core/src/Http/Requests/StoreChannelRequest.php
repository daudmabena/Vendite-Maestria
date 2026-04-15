<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreChannelRequest extends FormRequest
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
            'hostname' => ['nullable', 'string', 'max:65535'],
            'color' => ['nullable', 'string', 'max:65535'],
            'base_currency_id' => ['nullable', 'integer'],
            'default_locale_id' => ['nullable', 'integer'],
            'enabled' => ['nullable', 'boolean'],
        ];
    }
}
