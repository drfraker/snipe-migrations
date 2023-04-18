<?php

namespace Drfraker\SnipeMigrations\Tests;

use Drfraker\SnipeMigrations\Snipe;
use Drfraker\SnipeMigrations\SnipeDatabaseState;
use Drfraker\SnipeMigrations\SnipeMigrationsServiceProvider;
use Illuminate\Support\Facades\File;
use phpmock\mockery\PHPMockery;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /** @var Snipe */
    protected $snipe;

    /**
     * The absolute path to the "snapshots" folder.
     * @var string
     */
    protected $snipeFolder;

    /**
     * The full path to the snipe_snapshot.sql file.
     * @var string
     */
    protected $snapshotFile;

    /**
     * The full path to the .snipe file.
     * @var string
     */
    protected $snipeFile;

    protected function setUp(): void
    {
        parent::setUp();

        // This folder will reside in the orchestra sandbox environment
        // placed at vendor/orchestra/testbench-core/laravel
        $this->snipeFolder = base_path('vendor/drfraker/snipe-migrations/snapshots');

        $this->snapshotFile = config('snipe.snapshot-location');
        $this->snipeFile = config('snipe.snipefile-location');

        // Reset state before each run
        $this->clearSnapshotDir();
        $this->clearMigrationsDir();
        $this->resetDatabaseState();

        $this->snipe = new Snipe();

        // Add support for native method mocking. We pre-define the exec method here.
        // If we would just call the mock method in our tests where we need it, PHP
        // would have already cached the call to the native method instead.
        PHPMockery::define('Drfraker\SnipeMigrations', 'exec');
    }

    protected function getPackageProviders($app)
    {
        return [SnipeMigrationsServiceProvider::class];
    }

    protected function mimicInMemoryDatabase(): void
    {
        config()->set([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
    }

    protected function clearSnapshotDir()
    {
        if (! is_dir($this->snipeFolder)) {
            mkdir($this->snipeFolder, 0777, true);

            return;
        }

        // Prepare sandbox for subsequent runs
        if (file_exists($this->snipeFile)) {
            $this->assertTrue(unlink($this->snipeFile));
        }

        if (file_exists($this->snapshotFile)) {
            $this->assertTrue(unlink($this->snapshotFile));
        }
    }

    protected function clearMigrationsDir()
    {
        foreach (File::allFiles(database_path('migrations')) as $file) {
            if ($file->getFilename() !== '.gitkeep') {
                unlink($file->getRealPath());
            }
        }
    }

    protected function copyDefaultMigrations(): void
    {
        foreach (File::allFiles(base_path('migrations')) as $file) {
            copy($file->getRealPath(), database_path("migrations/{$file->getFilename()}"));
        }
    }

    protected function resetDatabaseState(): void
    {
        SnipeDatabaseState::$checkedForDatabaseFileChanges = false;
    }
}
