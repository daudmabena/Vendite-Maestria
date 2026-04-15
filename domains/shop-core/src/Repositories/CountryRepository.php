<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\ShopCore\Repositories\Contracts\CountryRepositoryInterface;
use Modules\ShopCore\Models\Country;

/**
 * @extends AbstractShopRepository<Country>
 */
final class CountryRepository extends AbstractShopRepository implements CountryRepositoryInterface
{
    public static function modelClass(): string
    {
        return Country::class;
    }

    public function findByCode(string $code): ?Country
    {
        /** @var Country|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
