<?php

declare(strict_types=1);

namespace Modules\ShopCore\Repositories\Contracts;

use Modules\ShopCore\Models\Locale;
use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;

/**
 * @extends CrudRepositoryInterface<Locale>
 */
interface LocaleRepositoryInterface extends CrudRepositoryInterface
{
    public function findByCode(string $code): ?Locale;
}
