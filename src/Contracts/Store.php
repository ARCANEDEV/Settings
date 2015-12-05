<?php namespace Arcanedev\Settings\Contracts;

/**
 * Interface  Store
 *
 * @package   Arcanedev\Settings\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Store
{
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
    public function get($key, $default = null);

    /**
     * Determine if a key exists in the settings data.
     *
     * @param  string  $key
     *
     * @return boolean
     */
    public function has($key);

    /**
     * Set a specific key to a value in the settings data.
     *
     * @param  string|array $key    Key string or associative array of key => value
     * @param  mixed        $value  Optional only if the first argument is an array
     */
    public function set($key, $value = null);

    /**
     * Unset a key in the settings data.
     *
     * @param  string  $key
     */
    public function forget($key);

    /**
     * Unset all keys in the settings data.
     */
    public function reset();

    /**
     * Get all settings data.
     *
     * @return array
     */
    public function all();

    /**
     * Save any changes done to the settings data.
     */
    public function save();
}
