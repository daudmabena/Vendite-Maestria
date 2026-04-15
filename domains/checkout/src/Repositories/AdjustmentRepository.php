<?php

declare(strict_types=1);

namespace Modules\Checkout\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\Checkout\Repositories\Contracts\AdjustmentRepositoryInterface;
use Modules\Checkout\Models\Adjustment;

/**
 * @extends AbstractShopRepository<Adjustment>
 */
final class AdjustmentRepository extends AbstractShopRepository implements AdjustmentRepositoryInterface
{
    public static function modelClass(): string
    {
        return Adjustment::class;
    }
}
