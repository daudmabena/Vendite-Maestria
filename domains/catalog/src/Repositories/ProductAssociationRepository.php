<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Modules\Catalog\Repositories\Contracts\ProductAssociationRepositoryInterface;
use Modules\Catalog\Models\ProductAssociation;
use Modules\ShopCore\Repositories\AbstractShopRepository;

/**
 * @extends AbstractShopRepository<ProductAssociation>
 */
final class ProductAssociationRepository extends AbstractShopRepository implements ProductAssociationRepositoryInterface
{
    public static function modelClass(): string
    {
        return ProductAssociation::class;
    }
}
