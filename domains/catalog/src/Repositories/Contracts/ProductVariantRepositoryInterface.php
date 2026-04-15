<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories\Contracts;

use Modules\Catalog\Models\ProductVariant;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<ProductVariant>
 */
interface ProductVariantRepositoryInterface extends CrudRepositoryInterface
{
}
