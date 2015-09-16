<?php namespace Arcanedev\Localization\Contracts;
use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException;
use Arcanedev\Localization\Exceptions\UnsupportedLocaleException;

/**
 * Interface  LocalizationInterface
 *
 * @package   Arcanedev\Localization\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface LocalizationInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Config repository.
     *
     * @return \Illuminate\Config\Repository
     */
    public function config();

    /**
     * Returns default locale.
     *
     * @return string
     */
    public function getDefaultLocale();

    /**
     * Return an array of all supported Locales.
     *
     * @throws UndefinedSupportedLocalesException
     *
     * @return LocaleCollection
     */
    public function getSupportedLocales();

    /**
     * Get supported locales keys.
     *
     * @return array
     *
     * @throws UndefinedSupportedLocalesException
     */
    public function getSupportedLocalesKeys();

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
     * Set and return current locale.
     *
     * @param  string  $locale
     *
     * @return string
     */
    public function setLocale($locale = null);

    /**
     * Returns current language.
     *
     * @return string
     */
    public function getCurrentLocale();

    /**
     * Sets the base url for the site.
     *
     * @param  string  $url
     */
    public function setBaseUrl($url);

    /**
     * Set current route name.
     *
     * @param  false|string  $routeName
     */
    public function setRouteName($routeName);

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
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
     * @throws UnsupportedLocaleException
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
     * @param  string|bool   $locale
     * @param  string|false  $url
     * @param  array         $attributes
     *
     * @throws UndefinedSupportedLocalesException
     * @throws UnsupportedLocaleException
     *
     * @return string|false
     */
    public function getLocalizedURL($locale = null, $url = null, $attributes = []);

    /**
     * Create an url from the uri.
     *
     * @param  string  $uri
     *
     * @return string
     */
    public function createUrlFromUri($uri);

    /* ------------------------------------------------------------------------------------------------
     |  Translation Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Returns an URL adapted to the route name and the locale given.
     *
     * @param  string|bool  $locale
     * @param  string       $transKey
     * @param  array        $attributes
     *
     * @throws UndefinedSupportedLocalesException
     * @throws UnsupportedLocaleException
     *
     * @return string|false
     */
    public function getURLFromRouteNameTranslated($locale, $transKey, $attributes = []);

    /**
     * Returns the translation key for a given path.
     *
     * @param  string  $path
     *
     * @return string|false
     */
    public function getRouteNameFromPath($path);

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if Locale exists on the supported locales collection.
     *
     * @param  string|bool  $locale
     *
     * @throws UndefinedSupportedLocalesException
     *
     * @return bool
     */
    public function isLocaleSupported($locale);

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Hide the default locale in URL ??
     *
     * @return bool
     */
    public function hideDefaultLocaleInURL();
}
