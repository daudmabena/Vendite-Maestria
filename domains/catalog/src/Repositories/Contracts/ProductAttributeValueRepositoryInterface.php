<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories\Contracts;

use Modules\Catalog\Models\ProductAttributeValue;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<ProductAttributeValue>
 */
interface ProductAttributeValueRepositoryInterface extends CrudRepositoryInterface
{
}
