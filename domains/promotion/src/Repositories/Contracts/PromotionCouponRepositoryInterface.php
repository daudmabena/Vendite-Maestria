<?php

declare(strict_types=1);

namespace Modules\Promotion\Repositories\Contracts;

use Modules\Promotion\Models\PromotionCoupon;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<PromotionCoupon>
 */
interface PromotionCouponRepositoryInterface extends CrudRepositoryInterface
{
}
