<?php

declare(strict_types=1);

namespace Modules\Brand\Providers;

use Modules\Brand\Services\TouchpointService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * Listens to events fired by other domains and records brand touchpoints
 * non-invasively — no changes needed in the originating domain.
 *
 * Additional listeners (OrderPlaced, ProductViewed, etc.) can be added here
 * once those events are fired from the checkout / catalog domains.
 */
class BrandEventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Auth login → "visit" touchpoint
        Event::listen(Login::class, function (Login $event) {
            /** @var \Illuminate\Foundation\Auth\User $user */
            $user = $event->user;

            if (! property_exists($user, 'id')) {
                return;
            }

            $service = $this->app->make(TouchpointService::class);
            $service->record((int) $user->id, 'visit', 'web');
        });
    }
}
