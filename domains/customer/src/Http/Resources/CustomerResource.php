<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Resources;

use Modules\Customer\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Customer
 */
final class CustomerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Customer $customer */
        $customer = $this->resource;

        return [
            'id' => $customer->id,
            'user_id' => $customer->user_id,
            'email' => $customer->email,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'phone_number' => $customer->phone_number,
            'subscribed_to_newsletter' => $customer->subscribed_to_newsletter,
        ];
    }
}
