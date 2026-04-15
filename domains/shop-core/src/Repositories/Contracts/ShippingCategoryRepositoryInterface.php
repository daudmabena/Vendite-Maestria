<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories\Contracts;

use Modules\ShopCore\Models\ShippingCategory;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<ShippingCategory>
 */
interface ShippingCategoryRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?ShippingCategory;
}
