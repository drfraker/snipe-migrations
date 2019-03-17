<?php

namespace Drfraker\SnipeMigrations;

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
        $snipefile = config('snipe.snipefile-location');

        if ($snipefile && file_exists($snipefile)) {
            try {
                unlink($snipefile);
                $this->info('Cleared snipe migration snapshot.');
            } catch (\Exception $exception) {
                $this->warn("Could not delete snipe migration file: {$exception->getMessage()}");
            }

            return;
        }

        $this->info('No Snipe migration snapshot found (it may have been cleared already).');
    }
}
