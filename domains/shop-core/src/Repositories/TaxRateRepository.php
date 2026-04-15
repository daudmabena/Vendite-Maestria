<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\ShopCore\Repositories\Contracts\TaxRateRepositoryInterface;
use Modules\ShopCore\Models\TaxRate;

/**
 * @extends AbstractShopRepository<TaxRate>
 */
final class TaxRateRepository extends AbstractShopRepository implements TaxRateRepositoryInterface
{
    public static function modelClass(): string
    {
        return TaxRate::class;
    }

    public function findByCode(string $code): ?TaxRate
    {
        /** @var TaxRate|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
