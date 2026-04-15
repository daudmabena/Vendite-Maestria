<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories\Contracts;

use Modules\Catalog\Models\ChannelPricing;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<ChannelPricing>
 */
interface ChannelPricingRepositoryInterface extends CrudRepositoryInterface
{
}
