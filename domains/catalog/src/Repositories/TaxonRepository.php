<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Modules\Catalog\Repositories\Contracts\TaxonRepositoryInterface;
use Modules\Catalog\Models\Taxon;
use Modules\ShopCore\Repositories\AbstractShopRepository;

/**
 * @extends AbstractShopRepository<Taxon>
 */
final class TaxonRepository extends AbstractShopRepository implements TaxonRepositoryInterface
{
    public static function modelClass(): string
    {
        return Taxon::class;
    }

    public function findByCode(string $code): ?Taxon
    {
        /** @var Taxon|null $m */
        $m = $this->query()->where('code', $code)->first();

        return $m;
    }
}
