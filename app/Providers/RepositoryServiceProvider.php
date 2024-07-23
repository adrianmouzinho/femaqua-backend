<?php

namespace App\Providers;

use App\Interfaces\ToolRepositoryInterface;
use App\Repositories\ToolRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ToolRepositoryInterface::class, ToolRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
