<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Storage Locations
    |--------------------------------------------------------------------------
    | By default, SnipeMigrations will store snipe files and database snapshots
    | in /vendor/drfraker/snipe-migrations/snapshots. If you would like to
    | change the location of the files, update the paths below.
    */
    'snapshot-location'  => base_path('vendor/drfraker/snipe-migrations/snapshots/').'snipe_snapshot.sql',
    'snipefile-location' => base_path('vendor/drfraker/snipe-migrations/snapshots/').'.snipe',

    /*
    |--------------------------------------------------------------------------
    | Database Seeding
    |--------------------------------------------------------------------------
    | By default SnipeMigrations will refresh the database, run all migrations,
    | and start each test with an empty database. If you would like to seed
    | the database after refreshing it, enable the setting below. A custom
    | class can be set, otherwise, the default DatabaseSeeder will run.
    */
    'seed-database'      => false,
    'seed-class'         => 'DatabaseSeeder',

    /*
    |--------------------------------------------------------------------------
    | Command Execution
    |--------------------------------------------------------------------------
    | Many systems have the mysql binaries already installed (e.g. Homestead).
    | In case the binaries lie at a different path or have a special prefix,
    | as seen in docker-based setups, they can be configured here.
    |
    | e.g. 'mysql' => 'docker-compose exec test_db mysql'
    */

    'binaries'          => [
        'mysql'     => env('SNIPE_BINARY_MYSQL', 'mysql'),
        'mysqldump' => env('SNIPE_BINARY_MYSQLDUMP', 'mysqldump'),
    ],

];
