<?php

declare(strict_types=1);

namespace Modules\Customer\Repositories\Contracts;

use Modules\Customer\Models\Customer;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Customer>
 */
interface CustomerRepositoryInterface extends CrudRepositoryInterface
{    public function findByEmail(string $email): ?Customer;
}
