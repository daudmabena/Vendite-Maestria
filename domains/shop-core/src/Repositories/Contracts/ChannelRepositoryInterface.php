<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories\Contracts;

use Modules\ShopCore\Models\Channel;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Channel>
 */
interface ChannelRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?Channel;
}
