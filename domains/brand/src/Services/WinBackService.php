<?php

declare(strict_types=1);

namespace Modules\Brand\Services;

use Modules\Brand\Notifications\WinBackNotification;
use Modules\Brand\Repositories\Contracts\CustomerEngagementRepositoryInterface;
use Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Notification;

final class WinBackService
{
    public function __construct(
        private readonly CustomerEngagementRepositoryInterface $engagements,
    ) {}

    /**
     * Find all customers silent for $days days and send them a win-back notification.
     */
    public function runWinBackPass(int $silentDays = 30): int
    {
        $lapsing = $this->engagements->findLapsing($silentDays);
        $sent    = 0;

        foreach ($lapsing as $engagement) {
            $customer = Customer::find($engagement->customer_id);

            if ($customer === null) {
                continue;
            }

            Notification::send($customer, new WinBackNotification($engagement));
            ++$sent;
        }

        return $sent;
    }
}
