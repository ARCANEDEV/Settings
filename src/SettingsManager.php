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
        return $this->getConfig('default', 'json');
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
        return new Stores\JsonStore(
            $this->app['files'],
            $this->getConfig('stores.json.path')
        );
    }

    /**
     * Create the Database driver store.
     *
     * @return string
     */
    public function createDatabaseDriver()
    {
        return new Stores\DatabaseStore(
            $this->getConfig('stores.database.connection'),
            $this->getConfig('stores.database.table')
        );
    }

    /**
     * Create the Memory driver store.
     *
     * @return string
     */
    public function createMemoryDriver()
    {
        return new Stores\MemoryStore;
    }

    /**
     * Create the Array driver store.
     *
     * @return string
     */
    public function createArrayDriver()
    {
        return new Stores\ArrayStore;
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

        return $config->get("settings.$key", $default);
    }
}
