# Snipe Migrations

Faster migrations for Laravel tests. 

The package takes a snapshot of your mysql database and imports the file rather than running all of your migrations. 
If you have a project with many migration files, this process can provide you with a massive speed improvement when 
initializing your test suite. This can be used as a replacement for the RefreshDatabase trait that is provided out
of the box with Laravel.

## Installation

Require the package using composer.

```bash
composer require drfraker/snipe-migrations
```

## Usage

```php
use Snipe;
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](./LICENSE.md)
