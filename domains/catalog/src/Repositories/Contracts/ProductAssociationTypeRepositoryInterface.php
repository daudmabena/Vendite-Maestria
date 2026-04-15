<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories\Contracts;

use Modules\Catalog\Models\ProductAssociationType;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<ProductAssociationType>
 */
interface ProductAssociationTypeRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?ProductAssociationType;
}
