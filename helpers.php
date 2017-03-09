<?php

if ( ! function_exists('localization')) {
    /**
     * Get the Localization instance.
     *
     * @return Arcanedev\Localization\Contracts\Localization
     */
    function localization()
    {
        return app(Arcanedev\Localization\Contracts\Localization::class);
    }

    /**
     * Translated route from name helper
     *
     */
    function routeTo($route, $parameters = [])
    {
        return localization()->getUrlFromRouteName(localization()->getCurrentLocale(), $route, $parameters);
    }
}
