<?php

namespace TheBachtiarz\EAV\Providers;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(abstract: \TheBachtiarz\EAV\Interfaces\Models\ModelInterface::class, concrete: \TheBachtiarz\EAV\Models\AbstractModel::class);
        $this->app->bind(abstract: \TheBachtiarz\EAV\Interfaces\Repositories\RepositoryInterface::class, concrete: \TheBachtiarz\EAV\Repositories\AbstractRepository::class);

        $this->app->bind(abstract: \TheBachtiarz\EAV\Interfaces\Models\EavInterface::class, concrete: \TheBachtiarz\EAV\Models\Eav::class);
        $this->app->bind(abstract: \TheBachtiarz\EAV\Interfaces\Repositories\EavRepositoryInterface::class, concrete: \TheBachtiarz\EAV\Repositories\EavRepository::class);
        $this->app->bind(abstract: \TheBachtiarz\EAV\Interfaces\Services\EavServiceInterface::class, concrete: \TheBachtiarz\EAV\Services\EavService::class);
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
