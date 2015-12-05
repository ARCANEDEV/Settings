<?php namespace Arcanedev\Settings;

use Illuminate\Support\Manager;

/**
 * Class     SettingsManager
 *
 * @package  Arcanedev\Settings
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SettingsManager extends Manager
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->getConfig('settings.default', 'json');
    }

    /* ------------------------------------------------------------------------------------------------
     |  Driver Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create the Json driver store.
     *
     * @return string
     */
    public function createJsonDriver()
    {
        return 'Json driver';
    }

    /**
     * Create the Database driver store.
     *
     * @return string
     */
    public function createDatabaseDriver()
    {
        return 'Database driver';
    }

    /**
     * Create the Memory driver store.
     *
     * @return string
     */
    public function createMemoryDriver()
    {
        return 'Memory driver';
    }

    /**
     * Create the Array driver store.
     *
     * @return string
     */
    public function createArrayDriver()
    {
        return 'Array driver';
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get config.
     *
     * @param  string      $key
     * @param  mixed|null  $default
     *
     * @return string
     */
    protected function getConfig($key, $default = null)
    {
        /** @var  \Illuminate\Config\Repository  $config */
        $config = $this->app['config'];

        return $config->get($key, $default);
    }
}
