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
        $this->registerSettingsManager();
        $this->registerSettingsStore();
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();

        $this->publishes([
            $this->getConfigFile() => config_path('settings.php')
        ], 'config');

        $this->publishes([
            $this->getBasePath() . '/database/migrations/' => database_path('migrations')
        ], 'migrations');
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
            \Arcanedev\Settings\Contracts\Store::class,
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the Settings Manager.
     */
    private function registerSettingsManager()
    {
        $this->singleton('arcanedev.settings.manager', function(Application $app) {
            return new SettingsManager($app);
        });
    }

    /**
     * Register the Settings Store.
     */
    private function registerSettingsStore()
    {
        $this->bind('arcanedev.settings.store', function(Application $app) {
            return $app->make('arcanedev.settings.manager')->driver();
        });

        $this->bind(
            \Arcanedev\Settings\Contracts\Store::class,
            'arcanedev.settings.store'
        );
    }
}
