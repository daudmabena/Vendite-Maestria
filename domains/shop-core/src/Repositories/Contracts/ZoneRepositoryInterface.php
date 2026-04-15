<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories\Contracts;

use Modules\ShopCore\Models\Zone;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Zone>
 */
interface ZoneRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?Zone;
}
