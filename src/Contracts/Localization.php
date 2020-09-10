<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Contracts;

use Illuminate\Http\Request;

/**
 * Interface  Localization
 *
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Localization
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Returns default locale.
     *
     * @return string
     */
    public function getDefaultLocale();

    /**
     * Return an array of all supported Locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getSupportedLocales();

    /**
     * Set the supported locales.
     *
     * @param  array  $supportedLocales
     *
     * @return self
     */
    public function setSupportedLocales(array $supportedLocales);

    /**
     * Get supported locales keys.
     *
     * @return array
     */
    public function getSupportedLocalesKeys();

    /**
     * Returns current language.
     *
     * @return string
     */
    public function getCurrentLocale();

    /**
     * Returns current language.
     *
     * @return \Arcanedev\Localization\Entities\Locale
     */
    public function getCurrentLocaleEntity();

    /**
     * Returns current locale name.
     *
     * @return string
     */
    public function getCurrentLocaleName();

    /**
     * Returns current locale script.
     *
     * @return string
     */
    public function getCurrentLocaleScript();

    /**
     * Returns current locale direction.
     *
     * @return string
     */
    public function getCurrentLocaleDirection();

    /**
     * Returns current locale native name.
     *
     * @return string
     */
    public function getCurrentLocaleNative();

    /**
     * Returns current locale regional.
     *
     * @return string
     */
    public function getCurrentLocaleRegional();

    /**
     * Get all locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getAllLocales();

    /**
     * Set and return current locale.
     *
     * @param  string  $locale
     *
     * @return string
     */
    public function setLocale($locale = null);

    /**
     * Sets the base url for the site.
     *
     * @param  string  $url
     *
     * @return $this
     */
    public function setBaseUrl($url);

    /**
     * Set route name from request.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function setRouteNameFromRequest(Request $request);

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Translate routes and save them to the translated routes array (used in the localize route filter).
     *
     * @param  string  $routeName
     *
     * @return string
     */
    public function transRoute($routeName);

    /**
     * Returns an URL adapted to $locale or current locale.
     *
     * @param  string       $url
     * @param  string|null  $locale
     *
     * @return string
     */
    public function localizeURL($url = null, $locale = null);

    /**
     * It returns an URL without locale (if it has it).
     *
     * @param  string|false  $url
     *
     * @return string
     */
    public function getNonLocalizedURL($url = null);

    /**
     * Returns an URL adapted to $locale.
     *
     * @param  string|null  $locale
     * @param  string|null  $url
     * @param  array        $attributes
     * @param  bool|bool    $showHiddenLocale
     *
     * @return string|false
     */
    public function getLocalizedURL($locale = null, $url = null, array $attributes = [], $showHiddenLocale = false);

    /**
     * Create an url from the uri.
     *
     * @param  string  $uri
     *
     * @return string
     */
    public function createUrlFromUri($uri);

    /* -----------------------------------------------------------------
     |  Translation Methods
     | -----------------------------------------------------------------
     */
    /**
     * Returns an URL adapted to the route name and the locale given.
     *
     * @param  string|bool  $locale
     * @param  string       $transKey
     * @param  array        $attributes
     * @param  bool|false   $showHiddenLocale
     *
     * @return string|false
     */
    public function getUrlFromRouteName($locale, $transKey, array $attributes = [], $showHiddenLocale = false);

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Hide the default locale in URL ??
     *
     * @return bool
     */
    public function isDefaultLocaleHiddenInUrl();

    /**
     * Check if Locale exists on the supported locales collection.
     *
     * @param  string|bool  $locale
     *
     * @return bool
     */
    public function isLocaleSupported($locale);
}
