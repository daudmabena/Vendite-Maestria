<?php

declare(strict_types=1);

namespace Modules\Promotion\Repositories\Contracts;

use Modules\Promotion\Models\PromotionRule;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<PromotionRule>
 */
interface PromotionRuleRepositoryInterface extends CrudRepositoryInterface
{
}
