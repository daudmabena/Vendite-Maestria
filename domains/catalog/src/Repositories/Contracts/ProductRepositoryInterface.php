<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories\Contracts;

use Modules\Catalog\Models\Product;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Product>
 */
interface ProductRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?Product;
}
