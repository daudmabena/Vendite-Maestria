<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreShipmentRequest extends FormRequest
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
            'order_id' => ['nullable', 'integer'],
            'shipping_method_id' => ['nullable', 'integer'],
            'state' => ['nullable', 'string', 'max:65535'],
            'tracking' => ['nullable', 'string', 'max:65535'],
            'shipped_at' => ['nullable', 'date'],
        ];
    }
}
