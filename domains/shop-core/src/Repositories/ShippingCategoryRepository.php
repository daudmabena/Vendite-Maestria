<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\ShopCore\Repositories\Contracts\ShippingCategoryRepositoryInterface;
use Modules\ShopCore\Models\ShippingCategory;

/**
 * @extends AbstractShopRepository<ShippingCategory>
 */
final class ShippingCategoryRepository extends AbstractShopRepository implements ShippingCategoryRepositoryInterface
{
    public static function modelClass(): string
    {
        return ShippingCategory::class;
    }

    public function findByCode(string $code): ?ShippingCategory
    {
        /** @var ShippingCategory|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
