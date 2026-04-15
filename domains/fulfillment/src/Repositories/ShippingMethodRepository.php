<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\Fulfillment\Repositories\Contracts\ShippingMethodRepositoryInterface;
use Modules\Fulfillment\Models\ShippingMethod;

/**
 * @extends ShopCoreAbstractShopRepository<ShippingMethod>
 */
final class ShippingMethodRepository extends ShopCoreAbstractShopRepository implements ShippingMethodRepositoryInterface
{
    public static function modelClass(): string
    {
        return ShippingMethod::class;
    }

    public function findByCode(string $code): ?ShippingMethod
    {
        /** @var ShippingMethod|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
