<?php

if ( ! function_exists('localization')) {
    /**
     * Get the Localization instance.
     *
     * @return \Arcanedev\Localization\Localization
     */
    function localization()
    {
        return app('arcanedev.localization');
    }
}
