# 2. Installation

## Composer

You can install this package via [Composer](http://getcomposer.org/) by running this command: `composer require arcanedev/settings`.

Or by adding the package to your `composer.json`.

```json
{
    "require": {
        "arcanedev/settings": "~1.0"
    }
}
```

Then install it via `composer install` or `composer update`.

## Setup

Once the package is installed, you can register the service provider in `config/app.php` in the `providers` array:

```php
// config/app.php

'providers' => [
    ...
    Arcanedev\Settings\SettingsServiceProvider::class,
],
```

**Optional :** You can also register the facade:

```php
// config/app.php

'aliases' => [
    ...
    'Setting' => Arcanedev\Settings\Facades\Setting::class,
],
```

### Artisan commands

To publish the config & migrations files, run this command:

```bash
$ php artisan vendor:publish --provider="Arcanedev\Settings\SettingsServiceProvider"
```
