<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\ShopCore\Repositories\Contracts\ZoneRepositoryInterface;
use Modules\ShopCore\Models\Zone;

/**
 * @extends AbstractShopRepository<Zone>
 */
final class ZoneRepository extends AbstractShopRepository implements ZoneRepositoryInterface
{
    public static function modelClass(): string
    {
        return Zone::class;
    }

    public function findByCode(string $code): ?Zone
    {
        /** @var Zone|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
