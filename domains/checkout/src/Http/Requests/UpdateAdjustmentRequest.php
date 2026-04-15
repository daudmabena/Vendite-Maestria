<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateAdjustmentRequest extends FormRequest
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
            'type' => ['sometimes', 'string', 'max:65535'],
            'label' => ['sometimes', 'string', 'max:65535'],
            'amount' => ['sometimes', 'integer'],
            'neutral' => ['sometimes', 'boolean'],
            'locked' => ['sometimes', 'boolean'],
            'origin_code' => ['sometimes', 'string', 'max:65535'],
            'details' => ['sometimes', 'array'],
        ];
    }
}
