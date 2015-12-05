<?php namespace Arcanedev\Settings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class     Setting
 *
 * @package  Arcanedev\Settings\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Setting extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'arcanedev.settings.manager'; }
}
