<?php namespace Arcanedev\Settings\Tests\Stubs;

use Orchestra\Testbench\Http\Kernel;

/**
 * Class     HttpKernel
 *
 * @package  Arcanedev\Settings\Tests\Stubs
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class HttpKernel extends Kernel
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Arcanedev\Settings\Http\Middleware\SettingsMiddleware::class
    ];
}
