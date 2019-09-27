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

    /** @test */
    public function it_detects_file_changes_in_the_migration_folder()
    {
        Artisan::shouldReceive('call');

        // The first time we run snipe, we have no migrations
        $this->snipe->importSnapshot();

        $this->assertFileExists($this->snipeFile);
        $this->assertEquals(0, file_get_contents($this->snipeFile));

        $this->copyDefaultMigrations();

        // Let's do a re-run
        $this->resetDatabaseState();
        $this->snipe->importSnapshot();

        // This time the changes should have been picked up
        $this->assertGreaterThan(0, file_get_contents($this->snipeFile));
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
