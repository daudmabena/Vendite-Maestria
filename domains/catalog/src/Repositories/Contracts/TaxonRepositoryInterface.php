<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories\Contracts;

use Modules\Catalog\Models\Taxon;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Taxon>
 */
interface TaxonRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?Taxon;
}
