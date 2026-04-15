<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories\Contracts;

use Modules\Catalog\Models\ProductOption;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<ProductOption>
 */
interface ProductOptionRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?ProductOption;
}
