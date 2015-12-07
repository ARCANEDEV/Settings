# 4. Usage

## Table of contents

1. [Methods](#methods)
2. [Auto-saving](#auto-saving)
3. [Storages]
  * JSON storage
  * Database storage
  * Custom storage

You can either access the Setting's store via its facade or inject it by type-hinting towards the abstract class.

## Methods

```php
Setting::set('foo', 'bar');

var_dump(Setting::get('foo'));

// 'bar'
```

To check if the settings has a specific record, use `Setting::has()`:

```php
Setting::set('foo', 'bar');

var_dump(Setting::has('foo'));

// true

var_dump(Setting::has('baz'));

// false
```

You can specify a default value if the record was not found:

```php
var_dump(Setting::get('baz', 'Default value'));

// 'Default value'
```

You can set and get a value with a dot keys:

```php
Setting::set('foo.bar', 'baz');

var_dump(Setting::get('foo'));

// [
//   'bar' => 'baz',
// ]

var_dump(Setting::get('foo.bar'));

// 'baz'
```

To get all the settings, use `Setting::all()`:

```php
Setting::set('foo', 'bar');
Setting::set('baz', 'qux');

var_dump(Setting::all());

// [
//   'foo' => 'bar',
//   'baz' => 'qux',
// ]
```

To forget/remove a setting, use `Setting::forget()`:

```php
Setting::set('foo', 'bar');
Setting::set('baz', 'qux');

Setting::forget('foo');

var_dump(Setting::all());

// [
//   'foo' => 'bar',
// ]
```

To reset/remove all the settings, use `Setting::reset()`:

```php
Setting::set('foo', 'bar');
Setting::set('baz', 'qux');

Setting::reset();

var_dump(Setting::all());

// []
```

**Important :** Call the `Setting::save()` to save all changes you've made.

## Auto-saving

To save all changes without calling each time the `Setting::save()`, you simply add the middleware `SettingsMiddleware` at the end to your middleware list in `app\Http\Kernel.php`, settings will be saved automatically when the HTTP requests are terminated.

```php
// app/Http/Kernel.php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        // ... Other middlewares
        \Arcanedev\Settings\Http\Middleware\SettingsMiddleware::class,
    ];

    // ...
}
```

**Important :** You'll still need to call `Setting::save()` explicitly in console commands, queue workers etc.

## Storages

### JSON storage

For the JSON storage, you can modify the storage path of your json file on the fly by using `Setting::setPath($path)`.

### Database storage

To use the database storage, you need first migrate the settings table.

You can publish the settings migration by running :

`php artisan vendor:publish --provider="Arcanedev\Settings\SettingsServiceProvider" --tag=migrations`

Don't forget to run `php artisan migrate` to migrate the settings table.

Or by running the migration directly from the package : `php artisan migrate --path=vendor/arcanedev/settings/database/migrations`

After the migration, you can now use the same methods as mentioned above plus the methods specific to the database store.

For example, if you want to associate settings for a user in the same database, you need specify the extra columns:

```php
Setting::setExtraColumns([
    'user_id' => Auth::user()->id
]);

// Call the other settings methods ...
```

**Very Important :** Before specifying the extra columns, you must add these columns to your settings migration.

If you need more an additional control over the queried settings records, you can use the setConstraint method which takes a closure with two arguments:

  * `$model`  : is the eloquent Setting model.
  * `$insert` : is a boolean telling you whether the query is an insert or not. If it is an insert, you usually don't need to do anything to `$model`.

```php
Setting::setConstraint(function(\Arcanedev\Settings\Models\Setting $model, $insert) {
    if ($insert) {
        return $model;
    }

    return $model->where(/* Your condition here... */);
});
```

### Custom storages

This package uses the Laravel `Illuminate\Support\Manager` class under the hood, so it's easy to add your own custom session store driver if you want to store in some other way.

All you need to do is extend the abstract `Arcanedev\Settings\Bases\Store` class and implement the `Arcanedev\Settings\Contracts\Store` interface and register it with the `Setting::extend()`.

```php
<?php namespace YourNamespace;

use Arcanedev\Settings\Bases\Store;
use Arcanedev\Settings\Contracts\Store as StoreContract;

class MyCustomStore extends Store implements StoreContract
{
    // ... Your implementations
}
```

```php
Setting::extend('my-custom-store', function($app) {
    return $app->make(\YourNamespace\MyCustomStore::class);
});
```
