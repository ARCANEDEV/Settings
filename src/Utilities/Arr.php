<?php namespace Arcanedev\Settings\Utilities;

use Illuminate\Support\Arr as BaseArr;

/**
 * Class     Arr
 *
 * @package  Arcanedev\Settings\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Arr extends BaseArr
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array         $array
     * @param  array|string  $key
     * @param  mixed         $default
     *
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        return is_array($key)
            ? static::getArray($array, $key, $default)
            : parent::get($array, $key, $default);
    }

    /**
     * Get array by keys.
     *
     * @param  array  $input
     * @param  array  $keys
     * @param  mixed  $default
     *
     * @return array
     */
    protected static function getArray(array $input, array $keys, $default = null)
    {
        $output = [];

        foreach ($keys as $key) {
            static::set($output, $key, static::get($input, $key, $default));
        }

        return $output;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return array
     */
    public static function set(&$array, $key, $value)
    {
        // @codeCoverageIgnoreStart
        if (is_null($key)) {
            return $array = $value;
        }
        // @codeCoverageIgnoreEnd

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if ( ! isset($array[$key])) {
                $array[$key] = [];
            }
            elseif ( ! is_array($array[$key])) {
                throw new \UnexpectedValueException('Non-array segment encountered');
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}
