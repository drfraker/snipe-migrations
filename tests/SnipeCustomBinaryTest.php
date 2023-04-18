<?php

namespace Drfraker\SnipeMigrations\Tests;

/**
 * Custom test class to check setting the binary path via env variables.
 * This is necessary as we need to call "putenv" before the actual setUp() method gets called.
 * DotEnv is in "immutable mode", so any changes inside the tests would have no effect.
 */
class SnipeCustomBinaryTest extends TestCase
{
    private const ENV_MYSQL = 'SNIPE_BINARY_MYSQL';
    private const ENV_MYSQLDUMP = 'SNIPE_BINARY_MYSQLDUMP';

    private static $customMysql = 'phpunitmysql';
    private static $customMysqldump = 'phpunitmysqldump';

    protected function setUp(): void
    {
        // Define the custom binary path we want to use
        putenv(self::ENV_MYSQL.'='.self::$customMysql);
        putenv(self::ENV_MYSQLDUMP.'='.self::$customMysqldump);

        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        putenv(self::ENV_MYSQL);
        putenv(self::ENV_MYSQLDUMP);
    }

    /** @test */
    public function it_allows_custom_binary_paths_based_on_environment_variables()
    {
        $this->assertEquals(self::$customMysql, config('snipe.binaries.mysql'));
        $this->assertEquals(self::$customMysqldump, config('snipe.binaries.mysqldump'));
    }
}
