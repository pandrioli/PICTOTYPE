<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CustomClasses;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(NotificationManager::class, function($app) {
          return new NotificationManager();
        });
    }
}
