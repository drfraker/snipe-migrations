<?php
/**
 * Created by PhpStorm.
 * User: dustin
 * Date: 2019-02-19
 * Time: 15:44
 */

namespace Drfraker\SnipeMigrations\Tests;

use Drfraker\SnipeMigrations\SnipeMigrationsServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [SnipeMigrationsServiceProvider::class];
    }
}