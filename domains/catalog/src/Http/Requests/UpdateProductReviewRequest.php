<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProductReviewRequest extends FormRequest
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
            'product_id' => ['sometimes', 'integer'],
            'customer_id' => ['sometimes', 'integer'],
            'rating' => ['sometimes', 'string', 'max:65535'],
            'title' => ['sometimes', 'string', 'max:65535'],
            'comment' => ['sometimes', 'string', 'max:65535'],
            'status' => ['sometimes', 'string', 'max:65535'],
            'accepted_at' => ['sometimes', 'date'],
            'rejected_at' => ['sometimes', 'date'],
        ];
    }
}
