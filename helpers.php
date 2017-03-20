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
}

if ( ! function_exists('localized_route')) {
    /**
     * Get a localized URL with a given trans route name.
     *
     * @return string
     */
    function localized_route($route, $parameters = [], $locale = null)
    {
        if (is_null($locale))
            $locale = localization()->getCurrentLocale();

        return localization()->getUrlFromRouteName($locale, $route, $parameters);
    }
}
