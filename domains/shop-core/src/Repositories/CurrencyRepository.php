<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\ShopCore\Repositories\Contracts\CurrencyRepositoryInterface;
use Modules\ShopCore\Models\Currency;

/**
 * @extends AbstractShopRepository<Currency>
 */
final class CurrencyRepository extends AbstractShopRepository implements CurrencyRepositoryInterface
{
    public static function modelClass(): string
    {
        return Currency::class;
    }

    public function findByCode(string $code): ?Currency
    {
        /** @var Currency|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
