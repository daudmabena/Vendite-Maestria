<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories\Contracts;

use Modules\ShopCore\Models\TaxRate;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<TaxRate>
 */
interface TaxRateRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?TaxRate;
}
