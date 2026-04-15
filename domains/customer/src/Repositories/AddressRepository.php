<?php

declare(strict_types=1);

namespace Modules\Customer\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\Customer\Repositories\Contracts\AddressRepositoryInterface;
use Modules\Customer\Models\Address;

/**
 * @extends ShopCoreAbstractShopRepository<Address>
 */
final class AddressRepository extends ShopCoreAbstractShopRepository implements AddressRepositoryInterface
{
    public static function modelClass(): string
    {
        return Address::class;
    }
}
