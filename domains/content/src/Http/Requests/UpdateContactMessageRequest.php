<?php

declare(strict_types=1);

namespace Modules\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateContactMessageRequest extends FormRequest
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
            'email' => ['sometimes', 'string', 'max:65535'],
            'name' => ['sometimes', 'string', 'max:65535'],
            'subject' => ['sometimes', 'string', 'max:65535'],
            'message' => ['sometimes', 'string', 'max:65535'],
            'status' => ['sometimes', 'string', 'max:65535'],
            'resolved_at' => ['sometimes', 'date'],
            'meta' => ['sometimes', 'string', 'max:65535'],
        ];
    }
}
