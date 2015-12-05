<?php namespace Arcanedev\Settings\Bases;

use Arcanedev\Settings\Utilities\Arr;

/**
 * Class     Store
 *
 * @package  Arcanedev\Settings\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Store
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The settings data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Whether the store has changed since it was last loaded.
     *
     * @var bool
     */
    protected $unsaved = false;

    /**
     * Whether the settings data are loaded.
     *
     * @var bool
     */
    protected $loaded = false;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get a specific key from the settings data.
     *
     * @param  string|array $key
     * @param  mixed        $default Optional default value.
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $this->checkLoaded();

        return Arr::get($this->data, $key, $default);
    }

    /**
     * Determine if a key exists in the settings data.
     *
     * @param  string  $key
     *
     * @return boolean
     */
    public function has($key)
    {
        $this->checkLoaded();

        return Arr::has($this->data, $key);
    }

    /**
     * Set a specific key to a value in the settings data.
     *
     * @param  string|array $key    Key string or associative array of key => value
     * @param  mixed        $value  Optional only if the first argument is an array
     */
    public function set($key, $value = null)
    {
        $this->checkLoaded();
        $this->unsaved = true;

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                Arr::set($this->data, $k, $v);
            }
        } else {
            Arr::set($this->data, $key, $value);
        }
    }

    /**
     * Unset a key in the settings data.
     *
     * @param  string  $key
     */
    public function forget($key)
    {
        $this->unsaved = true;

        if ($this->has($key)) {
            Arr::forget($this->data, $key);
        }
    }

    /**
     * Unset all keys in the settings data.
     */
    public function reset()
    {
        $this->unsaved = true;
        $this->data    = [];
    }

    /**
     * Get all settings data.
     *
     * @return array
     */
    public function all()
    {
        $this->checkLoaded();

        return $this->data;
    }

    /**
     * Save any changes done to the settings data.
     */
    public function save()
    {
        if ( ! $this->unsaved) {
            return;
        }

        $this->write($this->data);
        $this->unsaved = false;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the settings data has been loaded.
     */
    protected function checkLoaded()
    {
        if ( ! $this->loaded) {
            $this->data   = $this->read();
            $this->loaded = true;
        }
    }

    /**
     * Read the data from the store.
     *
     * @return array
     */
    abstract protected function read();

    /**
     * Write the data into the store.
     *
     * @param  array  $data
     */
    abstract protected function write(array $data);
}
