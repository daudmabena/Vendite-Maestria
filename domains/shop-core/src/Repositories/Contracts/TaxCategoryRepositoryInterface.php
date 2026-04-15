<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories\Contracts;

use Modules\ShopCore\Models\TaxCategory;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<TaxCategory>
 */
interface TaxCategoryRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?TaxCategory;
}
