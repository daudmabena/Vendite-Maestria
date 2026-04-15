<?php

declare(strict_types=1);

namespace Modules\Customer\Repositories\Contracts;

use Modules\Customer\Models\Address;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Address>
 */
interface AddressRepositoryInterface extends CrudRepositoryInterface
{
}
