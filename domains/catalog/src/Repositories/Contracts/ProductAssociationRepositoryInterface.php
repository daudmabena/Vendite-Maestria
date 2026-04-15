<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories\Contracts;

use Modules\Catalog\Models\ProductAssociation;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<ProductAssociation>
 */
interface ProductAssociationRepositoryInterface extends CrudRepositoryInterface
{
}
