<?php

namespace Drfraker\SnipeMigrations;

use Illuminate\Support\ServiceProvider;

class SnipeMigrationsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/snipe.php' => config_path('snipe.php'),
        ], 'snipe-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SnipeClearCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/snipe.php', 'snipe');
    }
}
