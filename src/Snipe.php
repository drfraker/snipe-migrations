<?php

namespace Drfraker\SnipeMigrations;

use Exception;
use Illuminate\Support\Facades\Artisan;

class Snipe
{
    /**
     * Make sure the testing database is up to date.
     *
     */
    public function importSnapshot()
    {
        if ($this->usingInMemoryDatabase()) {
            throw new Exception('Snipe Migrations is not yet configured to handle in memory databases');
        }

        $this->migrationChanges()
            ? $this->newSnapshot()
            : $this->importDatabase();

    }

    /**
     * Determine if an in-memory database is being used.
     * This isn't set up yet, and throws an error if the user is using :memory: sqlite.
     *
     * @return bool
     */
    protected function usingInMemoryDatabase()
    {
        $default = config('database.default');

        return config("database.connections.$default.database") === ':memory:';
    }

    /**
     * Determine if there have been migration changes since the last time the snapshot was updated.
     *
     * @return bool
     */
    protected function migrationChanges()
    {
        $snipeFile = config('snipe.snipefile-location');
        $snipeDumpFile = config('snipe.snapshot-location');

        $storedTimeSum = file_exists($snipeFile) ? file_get_contents($snipeFile) : 0;

        $timeSum = collect(scandir(database_path('migrations')))->reject(function ($file) {
            return $file === '.' || $file === '..';
        })->sum(function ($file) {
            return filemtime(database_path('migrations/'.$file));
        });

        if(! $storedTimeSum || (int) $storedTimeSum !== $timeSum || ! file_exists($snipeDumpFile)) {
            // store the new time sum.
            file_put_contents($snipeFile, $timeSum);

            return true;
        }

        return false;
    }

    /**
     * Generate a new snapshot of the MySql database.
     */
    protected function newSnapshot()
    {

        Artisan::call('migrate:fresh');

        $storageLocation = config('snipe.snapshot-location');

        // Store a snapshot of the db after migrations run.
        exec("mysqldump --no-data -u {$this->getDbUsername()} --password={$this->getDbPassword()} {$this->getDbName()} > {$storageLocation} 2>/dev/null");
    }

    /**
     * Import the snapshot file into the database if it hasn't been imported yet.
     */
    protected function importDatabase()
    {
        if (! SnipeDatabaseState::$imported) {
            $dumpfile = config('snipe.snapshot-location');

            exec("mysql -u {$this->getDbUsername()} --password={$this->getDbPassword()} {$this->getDbName()} < {$dumpfile} 2>/dev/null");

            SnipeDatabaseState::$imported = true;
        }
    }

    /**
     * Get the database connection from the config settings.
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function getDatabaseConnection()
    {
        return config('database.default');
    }

    /**
     * Get the Database username from the config settings.
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function getDbUsername()
    {
        $connection = $this->getDatabaseConnection();

        return config("database.connections.{$connection}.username");
    }

    /**
     * Get the database password from the config settings.
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function getDbPassword()
    {
        $connection = $this->getDatabaseConnection();

        return config("database.connections.{$connection}.password");
    }

    /**
     * Get the name of the database from config settings.
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function getDbName()
    {
        $connection = $this->getDatabaseConnection();

        return config("database.connections.{$connection}.database");
    }
}