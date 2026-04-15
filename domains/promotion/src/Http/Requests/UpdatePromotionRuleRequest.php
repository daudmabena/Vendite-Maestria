<?php

declare(strict_types=1);

namespace Modules\Promotion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdatePromotionRuleRequest extends FormRequest
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
            'promotion_id' => ['sometimes', 'integer'],
            'type' => ['sometimes', 'string', 'max:65535'],
            'configuration' => ['sometimes', 'array'],
        ];
    }
}
