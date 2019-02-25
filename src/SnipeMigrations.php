<?php

namespace Drfraker\SnipeMigrations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

trait SnipeMigrations
{
    public function setUpTraits()
    {
        $uses = array_flip(class_uses_recursive(static::class));
        if (isset($uses[RefreshDatabase::class]) || isset($uses[DatabaseTransactions::class])) {
            (new Snipe())->importSnapshot();
            RefreshDatabaseState::$migrated = true;
        }

        parent::setUpTraits();
    }
}
