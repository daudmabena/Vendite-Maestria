<?php

declare(strict_types=1);

namespace Modules\Promotion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdatePromotionRequest extends FormRequest
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
            'exclusive' => ['sometimes', 'boolean'],
            'priority' => ['sometimes', 'integer'],
            'usage_limit' => ['sometimes', 'integer'],
            'used' => ['sometimes', 'integer'],
            'starts_at' => ['sometimes', 'date'],
            'ends_at' => ['sometimes', 'date'],
            'coupon_based' => ['sometimes', 'boolean'],
            'applies_to_discounted' => ['sometimes', 'boolean'],
            'enabled' => ['sometimes', 'boolean'],
        ];
    }
}
