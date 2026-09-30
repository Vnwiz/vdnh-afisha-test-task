<?php

namespace App\Providers;

use App\Modules\Events\Application\Repositories\EventCategoryRepository;
use App\Modules\Events\Application\Repositories\EventRepository;
use App\Modules\Events\Application\Services\EventCategoryService;
use App\Modules\Events\Application\Services\EventService;
use App\Modules\Events\Domain\Repositories\EventCategoryRepositoryInterface;
use App\Modules\Events\Domain\Repositories\EventRepositoryInterface;
use App\Modules\Events\Domain\Services\EventCategoryServiceInterface;
use App\Modules\Events\Domain\Services\EventServiceInterface;
use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Events module
        $this->app->bind(EventServiceInterface::class, EventService::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(EventCategoryServiceInterface::class, EventCategoryService::class);
        $this->app->bind(EventCategoryRepositoryInterface::class, EventCategoryRepository::class);
    }

    public function boot(): void
    {
        // Роуты регистрируются через routes/api.php
    }
}
