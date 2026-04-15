<?php

namespace Modules\Content\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Content\Repositories\ContactMessageRepository;
use Modules\Content\Repositories\Contracts\ContactMessageRepositoryInterface;

class ContentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContactMessageRepositoryInterface::class, ContactMessageRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(dirname(__DIR__, 2).'/routes/content-routes.php');
        $this->loadMigrationsFrom(dirname(__DIR__, 2).'/database/migrations');
    }
}
