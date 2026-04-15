<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\ShopCore\Repositories\Contracts\LocaleRepositoryInterface;
use Modules\ShopCore\Models\Locale;

/**
 * @extends AbstractShopRepository<Locale>
 */
final class LocaleRepository extends AbstractShopRepository implements LocaleRepositoryInterface
{
    public static function modelClass(): string
    {
        return Locale::class;
    }

    public function findByCode(string $code): ?Locale
    {
        /** @var Locale|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
