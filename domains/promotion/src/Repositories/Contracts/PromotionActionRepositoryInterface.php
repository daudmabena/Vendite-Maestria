<?php

declare(strict_types=1);

namespace Modules\Promotion\Repositories\Contracts;

use Modules\Promotion\Models\PromotionAction;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<PromotionAction>
 */
interface PromotionActionRepositoryInterface extends CrudRepositoryInterface
{
}
