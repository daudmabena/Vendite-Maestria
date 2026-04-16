<?php

declare(strict_types=1);

namespace Modules\Brand\Repositories;

use Modules\Brand\Models\Campaign;
use Modules\Brand\Repositories\Contracts\CampaignRepositoryInterface;
use Modules\ShopCore\Repositories\AbstractShopRepository;
use Illuminate\Support\Collection;

/**
 * @extends AbstractShopRepository<Campaign>
 */
final class CampaignRepository extends AbstractShopRepository implements CampaignRepositoryInterface
{
    public static function modelClass(): string
    {
        return Campaign::class;
    }

    public function findActive(): Collection
    {
        return $this->query()->where('status', 'active')->get();
    }

    public function findByType(string $type): Collection
    {
        return $this->query()->where('type', $type)->get();
    }
}
