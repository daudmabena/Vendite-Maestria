<?php

declare(strict_types=1);

namespace Modules\Customer\Repositories\Contracts;

use Modules\Customer\Models\CustomerGroup;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<CustomerGroup>
 */
interface CustomerGroupRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?CustomerGroup;
}
