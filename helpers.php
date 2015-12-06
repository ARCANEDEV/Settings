<?php

if ( ! function_exists('settings')) {
    /**
     * Get the SettingManager instance.
     *
     * @return \Arcanedev\Settings\SettingsManager
     */
    function settings()
    {
        return app('arcanedev.settings.manager');
    }
}
