<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\ShopCore\Repositories\Contracts\TaxCategoryRepositoryInterface;
use Modules\ShopCore\Models\TaxCategory;

/**
 * @extends AbstractShopRepository<TaxCategory>
 */
final class TaxCategoryRepository extends AbstractShopRepository implements TaxCategoryRepositoryInterface
{
    public static function modelClass(): string
    {
        return TaxCategory::class;
    }

    public function findByCode(string $code): ?TaxCategory
    {
        /** @var TaxCategory|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
