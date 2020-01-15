<?php

namespace Drfraker\SnipeMigrations;

use Exception;
use Illuminate\Console\Command;

class SnipeClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snipe:clear';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $snipeFile = config('snipe.snipefile-location');

        if (! $snipeFile || ! file_exists($snipeFile)) {
            $this->info('No Snipe migration snapshot found (it may have been cleared already).');

            return;
        }

        try {
            unlink($snipeFile);
            $this->info('Cleared snipe migration snapshot.');
        } catch (Exception $exception) {
            $this->warn("Could not delete snipe migration file: {$exception->getMessage()}");
        }
    }
}
