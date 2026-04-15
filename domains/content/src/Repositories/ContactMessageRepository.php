<?php

declare(strict_types=1);

namespace Modules\Content\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\Content\Models\ContactMessage;
use Modules\Content\Repositories\Contracts\ContactMessageRepositoryInterface;

/**
 * @extends ShopCoreAbstractShopRepository<ContactMessage>
 */
final class ContactMessageRepository extends ShopCoreAbstractShopRepository implements ContactMessageRepositoryInterface
{
    public static function modelClass(): string
    {
        return ContactMessage::class;
    }
}

