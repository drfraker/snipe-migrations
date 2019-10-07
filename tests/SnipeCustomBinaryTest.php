<?php

namespace Drfraker\SnipeMigrations\Tests;

use phpmock\mockery\PHPMockery;
use Illuminate\Support\Facades\Artisan;

/**
 * Custom test class to check setting the binary path via env variables.
 * This is necessary as we need to call "putenv" before the actual setUp() method gets called.
 * DotEnv is in "immutable mode", so any changes inside the tests would have no effect.
 */
class SnipeCustomBinaryTest extends SnipeMigrationsTest
{
    private const ENV_VARIABLE = 'SNIPE_BINARY_MYSQLDUMP';
    private static $customBinary = 'phpunitdump';

    public function setUp(): void
    {
        // Define the custom binary path we want to use
        putenv(self::ENV_VARIABLE.'='.self::$customBinary);

        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        putenv(self::ENV_VARIABLE);
    }

    /** @test */
    public function it_allows_custom_binary_paths_based_on_environment_variables()
    {
        Artisan::shouldReceive('call')->withAnyArgs();

        PHPMockery::mock('Drfraker\SnipeMigrations', 'exec')
            ->withArgs(static function ($args) {
                return strpos($args, self::$customBinary) === 0;
            })->once();

        $this->snipe->importSnapshot();
    }
}
