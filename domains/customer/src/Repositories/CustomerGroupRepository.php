<?php

declare(strict_types=1);

namespace Modules\Customer\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\Customer\Repositories\Contracts\CustomerGroupRepositoryInterface;
use Modules\Customer\Models\CustomerGroup;

/**
 * @extends ShopCoreAbstractShopRepository<CustomerGroup>
 */
final class CustomerGroupRepository extends ShopCoreAbstractShopRepository implements CustomerGroupRepositoryInterface
{
    public static function modelClass(): string
    {
        return CustomerGroup::class;
    }

    public function findByCode(string $code): ?CustomerGroup
    {
        /** @var CustomerGroup|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
