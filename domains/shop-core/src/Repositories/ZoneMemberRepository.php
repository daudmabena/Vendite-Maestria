<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\ShopCore\Repositories\Contracts\ZoneMemberRepositoryInterface;
use Modules\ShopCore\Models\ZoneMember;

/**
 * @extends AbstractShopRepository<ZoneMember>
 */
final class ZoneMemberRepository extends AbstractShopRepository implements ZoneMemberRepositoryInterface
{
    public static function modelClass(): string
    {
        return ZoneMember::class;
    }
}
