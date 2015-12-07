# 3. Configuration

After publishing the package config file :

```php
// config/settings.php
<?php

return [
    /* ------------------------------------------------------------------------------------------------
     |  Settings driver
     | ------------------------------------------------------------------------------------------------
     |  Supported : 'json', 'database', 'memory', 'array'
     */
    'default' => 'json',

    /* ------------------------------------------------------------------------------------------------
     |  Settings supported drivers
     | ------------------------------------------------------------------------------------------------
     */
    'stores' => [
        'json'     => [
            'path' => storage_path('app/settings.json'),
        ],

        'database' => [
            'connection' => null,
            'table'      => 'settings',
        ],
    ],
];
```
