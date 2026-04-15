<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Modules\Catalog\Models\ProductOptionValue;
use Modules\Catalog\Repositories\Contracts\ProductOptionValueRepositoryInterface;
use Modules\ShopCore\Repositories\AbstractShopRepository;

/**
 * @extends AbstractShopRepository<ProductOptionValue>
 */
final class ProductOptionValueRepository extends AbstractShopRepository implements ProductOptionValueRepositoryInterface
{
    public static function modelClass(): string
    {
        return ProductOptionValue::class;
    }

    public function findByOptionAndCode(int $productOptionId, string $code): ?ProductOptionValue
    {
        /** @var ProductOptionValue|null $m */
        $m = $this->query()
            ->where('product_option_id', $productOptionId)
            ->where('code', $code)
            ->first();

        return $m;
    }
}
