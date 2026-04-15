<?php

declare(strict_types=1);

namespace Modules\Promotion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdatePromotionCouponRequest extends FormRequest
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
            'code' => ['sometimes', 'string', 'max:65535'],
            'usage_limit' => ['sometimes', 'integer'],
            'used' => ['sometimes', 'integer'],
            'expires_at' => ['sometimes', 'date'],
        ];
    }
}
