<?php

declare(strict_types=1);

namespace Modules\Promotion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StorePromotionActionRequest extends FormRequest
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
            'promotion_id' => ['nullable', 'integer'],
            'type' => ['nullable', 'string', 'max:65535'],
            'configuration' => ['nullable', 'array'],
        ];
    }
}
