<?php

namespace Drfraker\SnipeMigrations\Tests;

use phpmock\mockery\PHPMockery;
use Illuminate\Support\Facades\Artisan;

class SnipeMigrationsTest extends TestCase
{
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

    /** @test */
    public function it_allows_a_custom_prefix_for_executables()
    {
        Artisan::shouldReceive('call')->withAnyArgs();

        // Define the custom binary path we want to use
        static $customBinary = 'docker-compose exec db mysql';
        config()->set(['snipe.binaries.mysqldump' => $customBinary]);

        PHPMockery::mock('Drfraker\SnipeMigrations', 'exec')
            ->withArgs(static function ($args) use ($customBinary) {
                return strpos($args, $customBinary) === 0;
            })->once();

        $this->snipe->importSnapshot();
    }
}
