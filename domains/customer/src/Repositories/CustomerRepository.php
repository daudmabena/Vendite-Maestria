<?php

declare(strict_types=1);

namespace Modules\Customer\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\Customer\Repositories\Contracts\CustomerRepositoryInterface;
use Modules\Customer\Models\Customer;

/**
 * @extends ShopCoreAbstractShopRepository<Customer>
 */
final class CustomerRepository extends ShopCoreAbstractShopRepository implements CustomerRepositoryInterface
{
    public static function modelClass(): string
    {
        return Customer::class;
    }

    public function findByEmail(string $email): ?Customer
    {
        return $this->query()->where('email', $email)->first();
    }
}
