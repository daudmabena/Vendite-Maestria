<?php

declare(strict_types=1);

namespace Modules\Checkout\Repositories\Contracts;

use Modules\Checkout\Models\Adjustment;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Adjustment>
 */
interface AdjustmentRepositoryInterface extends CrudRepositoryInterface
{
}
