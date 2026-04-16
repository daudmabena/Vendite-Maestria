<?php

declare(strict_types=1);

namespace Modules\Brand\Services;

use Modules\Brand\Models\Campaign;
use Modules\Brand\Repositories\Contracts\CampaignRepositoryInterface;
use Illuminate\Validation\ValidationException;

final class CampaignService
{
    public function __construct(
        private readonly CampaignRepositoryInterface $campaigns,
    ) {}

    /** @param array<string, mixed> $data */
    public function create(array $data): Campaign
    {
        /** @var Campaign */
        return $this->campaigns->create($data);
    }

    /** @param array<string, mixed> $data */
    public function update(Campaign $campaign, array $data): Campaign
    {
        /** @var Campaign */
        return $this->campaigns->update($campaign, $data);
    }

    public function launch(Campaign $campaign): Campaign
    {
        if (! $campaign->isLaunchable()) {
            throw ValidationException::withMessages([
                'status' => "Campaign cannot be launched from status '{$campaign->status}'.",
            ]);
        }

        /** @var Campaign */
        return $this->campaigns->update($campaign, [
            'status'      => 'active',
            'launched_at' => now(),
        ]);
    }

    public function pause(Campaign $campaign): Campaign
    {
        /** @var Campaign */
        return $this->campaigns->update($campaign, ['status' => 'paused']);
    }
}
