<?php

declare(strict_types=1);

namespace Modules\Promotion\Repositories;

use Modules\ShopCore\Repositories\AbstractShopRepository;
use Modules\ShopCore\Repositories\AbstractShopRepository as ShopCoreAbstractShopRepository;
use Modules\Promotion\Repositories\Contracts\PromotionRepositoryInterface;
use Modules\Promotion\Models\Promotion;

/**
 * @extends ShopCoreAbstractShopRepository<Promotion>
 */
final class PromotionRepository extends ShopCoreAbstractShopRepository implements PromotionRepositoryInterface
{
    public static function modelClass(): string
    {
        return Promotion::class;
    }

    public function findByCode(string $code): ?Promotion
    {
        /** @var Promotion|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
