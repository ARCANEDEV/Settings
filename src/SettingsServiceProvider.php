<?php namespace Arcanedev\Settings;

use Arcanedev\Support\PackageServiceProvider as ServiceProvider;
use Illuminate\Foundation\Application;

/**
 * Class     SettingsServiceProvider
 *
 * @package  Arcanedev\Settings
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SettingsServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Vendor name.
     *
     * @var string
     */
    protected $vendor  = 'arcanedev';

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'settings';

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the base path of the package.
     *
     * @return string
     */
    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerConfig();

        $this->singleton('arcanedev.settings.manager', function($app) {
            return new SettingsManager($app);
        });

        $this->app->bind('arcanedev.settings.store', function(Application $app) {
            return $app->make('arcanedev.settings.manager')->driver();
        });
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'arcanedev.settings.manager',
            'arcanedev.settings.store',
        ];
    }
}
