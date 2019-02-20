<?php

namespace Drfraker\SnipeMigrations;

use Illuminate\Support\ServiceProvider;

class SnipeMigrationsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/snipe.php' => config_path('snipe.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/snipe.php', 'snipe');
    }
}