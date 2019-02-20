<?php

namespace Drfraker\SnipeMigrations;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class SnipeTestCase extends BaseTestCase
{
    public function setUpTraits()
    {
        parent::setUpTraits();

        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[DatabaseTransactions::class])) {
            (new Snipe())->importSnapshot();
        }
    }
}