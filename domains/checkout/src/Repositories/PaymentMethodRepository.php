<?php

declare(strict_types=1);

namespace Modules\Checkout\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\Checkout\Repositories\Contracts\PaymentMethodRepositoryInterface;
use Modules\Checkout\Models\PaymentMethod;

/**
 * @extends AbstractShopRepository<PaymentMethod>
 */
final class PaymentMethodRepository extends AbstractShopRepository implements PaymentMethodRepositoryInterface
{
    public static function modelClass(): string
    {
        return PaymentMethod::class;
    }

    public function findByCode(string $code): ?PaymentMethod
    {
        /** @var PaymentMethod|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
