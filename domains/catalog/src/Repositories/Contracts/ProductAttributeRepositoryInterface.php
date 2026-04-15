<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories\Contracts;

use Modules\Catalog\Models\ProductAttribute;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<ProductAttribute>
 */
interface ProductAttributeRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?ProductAttribute;
}
