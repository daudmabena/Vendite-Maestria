<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreAdjustmentRequest extends FormRequest
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
            'type' => ['nullable', 'string', 'max:65535'],
            'label' => ['nullable', 'string', 'max:65535'],
            'amount' => ['nullable', 'integer'],
            'neutral' => ['nullable', 'boolean'],
            'locked' => ['nullable', 'boolean'],
            'origin_code' => ['nullable', 'string', 'max:65535'],
            'details' => ['nullable', 'array'],
        ];
    }
}
