<?php

declare(strict_types=1);

namespace Modules\Promotion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StorePromotionRequest extends FormRequest
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
            'exclusive' => ['nullable', 'boolean'],
            'priority' => ['nullable', 'integer'],
            'usage_limit' => ['nullable', 'integer'],
            'used' => ['nullable', 'integer'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'coupon_based' => ['nullable', 'boolean'],
            'applies_to_discounted' => ['nullable', 'boolean'],
            'enabled' => ['nullable', 'boolean'],
        ];
    }
}
