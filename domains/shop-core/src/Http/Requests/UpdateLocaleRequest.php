<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateLocaleRequest extends FormRequest
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
        ];
    }
}
