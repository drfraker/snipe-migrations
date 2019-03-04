<?php

namespace Drfraker\SnipeMigrations\Tests;

use Drfraker\SnipeMigrations\Snipe;
use Illuminate\Support\Facades\Artisan;

class SnipeMigrationsTest extends TestCase
{
    protected $snipe;

    public function setUp()
    {
        parent::setUp();

        $this->clearSnapshotDir();

        $this->snipe = new Snipe();
    }

    /** @test */
    public function it_throws_an_error_if_the_application_is_using_in_memory_database()
    {
        $this->mimicInMemoryDatabase();

        Artisan::ShouldReceive('call')->never();

        $this->snipe->importSnapshot();
    }

    protected function mimicInMemoryDatabase(): void
    {
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
    }

    protected function clearSnapshotDir()
    {
        $snipefile = '../snapshots/.snipe';
        $snapshot = '../snapshots/snipe_snapshot.sql';

        if (file_exists($snipefile)) {
            unlink($snipefile);
        }

        if (file_exists($snapshot)) {
            unlink($snapshot);
        }
    }
}
