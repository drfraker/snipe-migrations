<?php

namespace Drfraker\SnipeMigrations\Tests;

use Drfraker\SnipeMigrations\Snipe;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Drfraker\SnipeMigrations\SnipeDatabaseState;

class SnipeMigrationsTest extends TestCase
{
    /** @var Snipe */
    protected $snipe;

    /**
     * The absolute path to the "snapshots" folder
     * @var
     */
    protected $snipeFolder;

    /**
     * The full path to the snipe_snapshot.sql file
     * @var string
     */
    protected $snapshotFile;

    /**
     * The full path to the .snip file
     * @var string
     */
    protected $snipeFile;


    public function setUp() :void
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
    }

    /** @test */
    public function it_throws_an_error_if_the_application_is_using_in_memory_database()
    {
        $this->mimicInMemoryDatabase();

        Artisan::shouldReceive('call')->never();

        $this->snipe->importSnapshot();
    }

    /** @test */
    public function it_calls_migration_commands_for_mysql_databases()
    {
        Artisan::shouldReceive('call')->with('migrate:fresh');

        $this->snipe->importSnapshot();
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
        if (!is_dir($this->snipeFolder)) {
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

    protected function resetDatabaseState(): void
    {
        SnipeDatabaseState::$checkedForDatabaseFileChanges = false;
    }
}
