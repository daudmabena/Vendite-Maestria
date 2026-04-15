<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories\Contracts;

use Modules\Catalog\Models\ProductOptionValue;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<ProductOptionValue>
 */
interface ProductOptionValueRepositoryInterface extends CrudRepositoryInterface
{
    public function findByOptionAndCode(int $productOptionId, string $code): ?ProductOptionValue;
}
