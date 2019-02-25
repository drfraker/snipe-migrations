<?php

namespace Drfraker\SnipeMigrations;

use Illuminate\Console\Command;

class SnipeClearCommand extends Command
{
    protected $signature = 'snipe:clear';

    public function handle()
    {
        @unlink(config('snipe.snipefile-location'));
        $this->info('Cleared snipe migration snapshot.');
    }
}
