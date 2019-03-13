![build status](https://travis-ci.com/drfraker/snipe-migrations.svg?branch=master "build status")
![StyleCI](https://github.styleci.io/repos/171511006/shield?branch=master)

# Snipe Migrations

Blazing fast database migrations for Laravel tests. 

The package takes a snapshot of your mysql database and imports the schema to your test database rather than running 
all of your migrations when the test suite starts up. 

If you have a project with many migration files, this process can provide you with a massive speed improvement when 
initializing your test suite. This package can be used as a replacement for the RefreshDatabase trait that is provided out
of the box with Laravel.

As an example, we tested this on an application that takes about 4 seconds to run all migrations with RefreshDatabase. 
Using SnipeMigrations the tests start up in 200 ms.

## Requirements
1. Laravel >= 5.5
2. PHP >= 7.2
3. MySql or MariaDb, with separate database for testing.
	- For example if you have a development database for your application called `amazingapp`
	you would create a test database called `amazingapp_test` and add the details of the 
	database in your phpunit.xml file. `amazingapp_test` is the database that Snipe will keep in sync for you.

## Installation

Require the package using composer.

```bash
composer require --dev drfraker/snipe-migrations
```

## Usage

**After you've installed the package via composer**
1. Add the `SnipeMigrations` trait to your `tests/TestCase` file. Don't forget to import the class at the top of the file. Once you have added the `SnipeMigrations` trait, simply use the RefreshDatabase trait in any tests in which you wish to use database access, and Snipe will handle the rest for you.

When you're done, your `tests/TestCase.php` file should look like this.

```php
<?php

namespace Tests;

use Drfraker\SnipeMigrations\SnipeMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, SnipeMigrations;
}
```

2. By default, SnipeMigrations will store a `.snipe` file and the `snipe_snapshot.sql` file in `/vendor/drfraker/snipe-migrations/snapshots`. If you would like to change the location of the files follow the directions below to publish
the snipe config file.
	- To publish the snipe config file, run `php artisan vendor:publish` and select `snipe-config` from the list.

3. If the snipe_snapshot.sql file gets out of sync for any reason you can clear it by running `php artisan snipe:clear`. After running
this command your original database migrations will run again creating a new `snipe_snapshot.sql` file.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](./LICENSE.md)
