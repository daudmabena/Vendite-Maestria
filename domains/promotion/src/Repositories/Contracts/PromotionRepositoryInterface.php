<?php

declare(strict_types=1);

namespace Modules\Promotion\Repositories\Contracts;

use Modules\Promotion\Models\Promotion;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Promotion>
 */
interface PromotionRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?Promotion;
}
