<?php namespace Arcanedev\Localization\Contracts;

/**
 * Interface  RouteTranslatorInterface
 *
 * @package   Arcanedev\Localization\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface RouteTranslatorInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
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
     * @param  string  $currentRoute
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

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Translate routes and save them to the translated routes array (used in the localize route filter).
     *
     * @param  string  $route
     *
     * @return string
     */
    public function trans($route);

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
     * @param  string  $path       -  Path to check if it is a translated route
     * @param  string  $urlLocale  -  Language to check if the path exists
     *
     * @return string|false
     */
    public function findTranslatedRouteByPath($path, $urlLocale);

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if has current route.
     *
     * @return bool
     */
    public function hasCurrentRoute();
}
