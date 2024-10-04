<?php

namespace TheBachtiarz\EAV\Providers;

use TheBachtiarz\EAV\Interfaces\Models\EavInterface;
use TheBachtiarz\EAV\Interfaces\Models\ModelInterface;
use TheBachtiarz\EAV\Interfaces\Repositories\EavRepositoryInterface;
use TheBachtiarz\EAV\Interfaces\Repositories\RepositoryInterface;
use TheBachtiarz\EAV\Models\AbstractModel;
use TheBachtiarz\EAV\Models\Eav;
use TheBachtiarz\EAV\Repositories\AbstractRepository;
use TheBachtiarz\EAV\Repositories\EavRepository;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(abstract: ModelInterface::class, concrete: AbstractModel::class);
        $this->app->bind(abstract: RepositoryInterface::class, concrete: AbstractRepository::class);

        $this->app->bind(abstract: EavInterface::class, concrete: Eav::class);
        $this->app->bind(abstract: EavRepositoryInterface::class, concrete: EavRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $publishName = 'thebachtiarz-eav';

        $this->publishes([__DIR__ . '/../../database/migrations' => database_path('migrations')], "$publishName-migrations");
    }
}
