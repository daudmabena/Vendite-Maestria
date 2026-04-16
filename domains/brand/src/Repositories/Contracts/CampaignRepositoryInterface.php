<?php

declare(strict_types=1);

namespace Modules\Brand\Repositories\Contracts;

use Modules\ShopCore\Repositories\Contracts\CrudRepositoryInterface;
use Modules\Brand\Models\Campaign;
use Illuminate\Support\Collection;

/**
 * @extends CrudRepositoryInterface<Campaign>
 */
interface CampaignRepositoryInterface extends CrudRepositoryInterface
{
    public function findActive(): Collection;

    public function findByType(string $type): Collection;
}
