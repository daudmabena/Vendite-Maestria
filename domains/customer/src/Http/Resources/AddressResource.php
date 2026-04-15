<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Resources;

use Modules\Customer\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Address
 */
final class AddressResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Address $address */
        $address = $this->resource;

        return [
            'id' => $address->id,
            'customer_id' => $address->customer_id,
            'first_name' => $address->first_name,
            'last_name' => $address->last_name,
            'phone_number' => $address->phone_number,
            'country_code' => $address->country_code,
            'province_code' => $address->province_code,
            'province_name' => $address->province_name,
            'street' => $address->street,
            'city' => $address->city,
            'postcode' => $address->postcode,
        ];
    }
}
