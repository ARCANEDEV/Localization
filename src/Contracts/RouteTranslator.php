<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Contracts;

use Arcanedev\Localization\Entities\LocaleCollection;

/**
 * Interface  RouteTranslator
 *
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface RouteTranslator
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get current route.
     *
     * @return string
     */
    public function getCurrentRoute();

    /**
     * Set the current route.
     *
     * @param  false|string  $currentRoute
     *
     * @return self
     */
    public function setCurrentRoute($currentRoute);

    /**
     * Get translated routes.
     *
     * @return array
     */
    public function getTranslatedRoutes();

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Translate routes and save them to the translated routes array (used in the localize route filter).
     *
     * @param  string       $route
     * @param  string|null  $locale
     *
     * @return string
     */
    public function trans($route, $locale = null);

    /**
     * Get the translated route.
     *
     * @param  string                                             $baseUrl
     * @param  array|false                                        $parsedUrl
     * @param  string                                             $defaultLocale
     * @param  \Arcanedev\Localization\Entities\LocaleCollection  $supportedLocales
     *
     * @return string|false
     */
    public function getTranslatedRoute(
        $baseUrl, &$parsedUrl, $defaultLocale, LocaleCollection $supportedLocales
    );

    /**
     * Returns the translation key for a given path.
     *
     * @param  string  $uri
     * @param  string  $locale
     *
     * @return false|string
     */
    public function getRouteNameFromPath($uri, $locale);

    /**
     * Returns the translated route for the path and the url given.
     *
     * @param  string  $path    -  Path to check if it is a translated route
     * @param  string  $locale  -  Language to check if the path exists
     *
     * @return string|false
     */
    public function findTranslatedRouteByPath($path, $locale);

    /**
     * Get URL from route name.
     *
     * @param  string|bool  $locale
     * @param  string       $defaultLocale
     * @param  string       $transKey
     * @param  array        $attributes
     * @param  bool|false   $defaultHidden
     * @param  bool|false   $showHiddenLocale
     *
     * @return string
     */
    public function getUrlFromRouteName(
        $locale, $defaultLocale, $transKey, $attributes = [], $defaultHidden = false, $showHiddenLocale = false
    );

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if has current route.
     *
     * @return bool
     */
    public function hasCurrentRoute();

    /**
     * Determine if a translation exists.
     *
     * @param  string  $key
     * @param  string  $locale
     *
     * @return bool
     */
    public function hasTranslation($key, $locale = null);
}
