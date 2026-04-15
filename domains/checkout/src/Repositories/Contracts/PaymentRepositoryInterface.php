<?php

declare(strict_types=1);

namespace Modules\Checkout\Repositories\Contracts;

use Modules\Checkout\Models\Payment;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Payment>
 */
interface PaymentRepositoryInterface extends CrudRepositoryInterface
{
}
