<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories\Contracts;

use Modules\ShopCore\Models\Currency;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Currency>
 */
interface CurrencyRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?Currency;
}
