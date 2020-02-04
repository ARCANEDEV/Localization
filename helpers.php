<?php

declare(strict_types=1);

use Arcanedev\Localization\Contracts\Localization;

if ( ! function_exists('localization')) {
    /**
     * Get the Localization instance.
     *
     * @return Arcanedev\Localization\Contracts\Localization
     */
    function localization(): Localization
    {
        return app(Localization::class);
    }
}

if ( ! function_exists('localized_route')) {
    /**
     * Get a localized URL with a given trans route name.
     *
     * @param  string       $transRoute
     * @param  array        $attributes
     * @param  string|null  $locale
     *
     * @return string
     */
    function localized_route($transRoute, array $attributes = [], $locale = null)
    {
        if (is_null($locale))
            $locale = localization()->getCurrentLocale();

        return localization()->getUrlFromRouteName($locale, $transRoute, $attributes);
    }
}
