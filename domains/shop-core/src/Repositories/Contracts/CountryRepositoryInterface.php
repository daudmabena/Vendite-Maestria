<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories\Contracts;

use Modules\ShopCore\Models\Country;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Country>
 */
interface CountryRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?Country;
}
