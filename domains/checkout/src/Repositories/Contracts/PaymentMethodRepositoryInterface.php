<?php

declare(strict_types=1);

namespace Modules\Checkout\Repositories\Contracts;

use Modules\Checkout\Models\PaymentMethod;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<PaymentMethod>
 */
interface PaymentMethodRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?PaymentMethod;
}
