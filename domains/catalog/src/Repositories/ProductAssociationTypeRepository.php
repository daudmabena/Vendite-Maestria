<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Modules\Catalog\Repositories\Contracts\ProductAssociationTypeRepositoryInterface;
use Modules\Catalog\Models\ProductAssociationType;
use Modules\ShopCore\Repositories\AbstractShopRepository;

/**
 * @extends AbstractShopRepository<ProductAssociationType>
 */
final class ProductAssociationTypeRepository extends AbstractShopRepository implements ProductAssociationTypeRepositoryInterface
{
    public static function modelClass(): string
    {
        return ProductAssociationType::class;
    }

    public function findByCode(string $code): ?ProductAssociationType
    {
        /** @var ProductAssociationType|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
