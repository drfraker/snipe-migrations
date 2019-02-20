<?php

namespace Drfraker\SnipeMigrations\Tests;

use Drfraker\SnipeMigrations\Snipe;
use Illuminate\Support\Facades\Config;

class SnipeMigrationsTest extends TestCase
{
    protected $snipe;

    public function setUp()
    {
        parent::setUp();

        $this->snipe = new Snipe();
    }

    /** @test */
    public function it_throws_an_error_if_the_application_is_using_in_memory_database()
    {
        $this->mimicInMemoryDatabase();

        try {
            $this->snipe->importSnapshot();
        } catch (\Exception $e) {
            $this->assertInstanceOf(\Exception::class, $e);
            $this->assertEquals(
                'Snipe Migrations is not yet configured to handle in memory databases',
                $e->getMessage()
            );
        }
    }

    protected function mimicInMemoryDatabase(): void
    {
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
    }
}
