<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\ShopCore\Repositories\Contracts\ProvinceRepositoryInterface;
use Modules\ShopCore\Models\Province;

/**
 * @extends AbstractShopRepository<Province>
 */
final class ProvinceRepository extends AbstractShopRepository implements ProvinceRepositoryInterface
{
    public static function modelClass(): string
    {
        return Province::class;
    }
}
