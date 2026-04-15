<?php

declare(strict_types=1);

namespace Modules\Checkout\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\Checkout\Repositories\Contracts\PaymentRepositoryInterface;
use Modules\Checkout\Models\Payment;

/**
 * @extends AbstractShopRepository<Payment>
 */
final class PaymentRepository extends AbstractShopRepository implements PaymentRepositoryInterface
{
    public static function modelClass(): string
    {
        return Payment::class;
    }
}
