<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreShipmentUnitRequest extends FormRequest
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
            'shipment_id' => ['nullable', 'integer'],
            'order_item_unit_id' => ['nullable', 'integer'],
        ];
    }
}
