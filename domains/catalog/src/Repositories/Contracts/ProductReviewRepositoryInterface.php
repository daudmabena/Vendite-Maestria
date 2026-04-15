<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories\Contracts;

use Modules\Catalog\Models\ProductReview;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<ProductReview>
 */
interface ProductReviewRepositoryInterface extends CrudRepositoryInterface
{
}

