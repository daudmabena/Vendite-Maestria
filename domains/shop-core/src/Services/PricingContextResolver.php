<?php

declare(strict_types=1);

namespace Modules\ShopCore\Services;

use Modules\ShopCore\Models\Channel;
use Modules\Customer\Models\CustomerGroup;
use App\Models\User;
use Modules\ShopCore\Services\ShopContext;
use Illuminate\Http\Request;

final class PricingContextResolver
{
    public function __construct(
        private readonly ShopContext $shopContext,
    ) {}

    /**
     * @return array{channel: ?Channel, customer_group: ?CustomerGroup}
     */
    public function resolve(Request $request): array
    {
        /** @var User|null $user */
        $user = $request->user();

        return [
            'channel' => $this->shopContext->channel(),
            'customer_group' => $user?->customer?->group,
        ];
    }
}

