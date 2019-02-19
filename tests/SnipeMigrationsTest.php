<?php

namespace Drfraker\SnipeMigrations\Tests;

use PHPUnit\Framework\TestCase;
use Drfraker\SnipeMigrations\Snipe;

class SnipeMigrationsTest extends TestCase
{
    /** @test */
    public function it_works()
    {
        $snipe = new Snipe();
        $this->assertEquals('hello world', $snipe->hello());
    }
}
