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

        $this->publishes([__DIR__.'/SnipeTestCase.php' => base_path('/tests/SnipeTestCase.php')], 'stubs');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/snipe.php', 'snipe');
    }
}
