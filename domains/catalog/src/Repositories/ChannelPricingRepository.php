<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Modules\Catalog\Repositories\Contracts\ChannelPricingRepositoryInterface;
use Modules\Catalog\Models\ChannelPricing;
use Modules\ShopCore\Repositories\AbstractShopRepository;

/**
 * @extends AbstractShopRepository<ChannelPricing>
 */
final class ChannelPricingRepository extends AbstractShopRepository implements ChannelPricingRepositoryInterface
{
    public static function modelClass(): string
    {
        return ChannelPricing::class;
    }
}
