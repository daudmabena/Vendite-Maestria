<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateShipmentRequest extends FormRequest
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
            'order_id' => ['sometimes', 'integer'],
            'shipping_method_id' => ['sometimes', 'integer'],
            'state' => ['sometimes', 'string', 'max:65535'],
            'tracking' => ['sometimes', 'string', 'max:65535'],
            'shipped_at' => ['sometimes', 'date'],
        ];
    }
}
