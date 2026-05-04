<?php

namespace App\Providers;

use App\Repositories\NotificationRepository;
use Illuminate\Support\ServiceProvider;
use NotificationRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            NotificationRepositoryInterface::class,
            NotificationRepository::class
        )
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
