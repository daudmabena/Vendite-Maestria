<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\ShopCore\Repositories\Contracts\ChannelRepositoryInterface;
use Modules\ShopCore\Models\Channel;

/**
 * @extends AbstractShopRepository<Channel>
 */
final class ChannelRepository extends AbstractShopRepository implements ChannelRepositoryInterface
{
    public static function modelClass(): string
    {
        return Channel::class;
    }

    public function findByCode(string $code): ?Channel
    {
        /** @var Channel|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
