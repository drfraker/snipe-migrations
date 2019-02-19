<?php

namespace Drfraker\SnipeMigrations\Tests;

use Drfraker\SnipeMigrations\Snipe;
use PHPUnit\Framework\TestCase;

class SnipeMigrationsTest extends TestCase
{
    /** @test */

    public function it_works()
    {
        $snipe = new Snipe();
        $this->assertEquals('hello world', $snipe->hello());
    }
}