<?php namespace Arcanedev\Settings\Http\Middleware;

use Arcanedev\Settings\Contracts\Store as Settings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class     SettingsMiddleware
 *
 * @package  Arcanedev\Settings\Http\Middleware
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SettingsMiddleware
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var \Arcanedev\Settings\Contracts\Store
     */
    private $settings;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new save settings middleware
     *
     * @param  \Arcanedev\Settings\Contracts\Store  $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Perform any final actions for the request lifecycle.
     *
     * @param  \Illuminate\Http\Request                    $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     *
     * @SuppressWarnings("unused")
     */
    public function terminate(Request $request, Response $response)
    {
        $this->settings->save();
    }
}
