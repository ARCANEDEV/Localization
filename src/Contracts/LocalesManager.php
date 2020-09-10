<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Contracts;

/**
 * Interface  LocalesManager
 *
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface LocalesManager
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set and return current locale.
     *
     * @param  string|null  $locale
     *
     * @return string
     */
    public function setLocale($locale = null);

    /**
     * Get the default locale.
     *
     * @return string
     */
    public function getDefaultLocale();

    /**
     * Set the default locale.
     *
     * @param  string  $defaultLocale
     *
     * @return $this
     */
    public function setDefaultLocale($defaultLocale = null);

    /**
     * Returns current language.
     *
     * @return string
     */
    public function getCurrentLocale();

    /**
     * Set the current locale.
     *
     * @param  string  $currentLocale
     *
     * @return $this
     */
    public function setCurrentLocale($currentLocale);

    /**
     * Get the current locale entity.
     *
     * @return \Arcanedev\Localization\Entities\Locale
     */
    public function getCurrentLocaleEntity();

    /**
     * Get all locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getAllLocales();

    /**
     * Get supported locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getSupportedLocales();

    /**
     * Get supported locales keys.
     *
     * @return array
     */
    public function getSupportedLocalesKeys();

    /**
     * Set supported locales.
     *
     * @param  array  $supportedLocales
     *
     * @return $this
     */
    public function setSupportedLocales(array $supportedLocales);

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if default is supported.
     *
     * @param  string  $defaultLocale
     *
     * @throws \Arcanedev\Localization\Exceptions\UnsupportedLocaleException
     */
    public function isDefaultLocaleSupported($defaultLocale);

    /**
     * Check if locale is supported.
     *
     * @param  string  $locale
     *
     * @return bool
     */
    public function isSupportedLocale($locale);

    /**
     * Hide the default locale in URL ??
     *
     * @return bool
     */
    public function isDefaultLocaleHiddenInUrl();

    /**
     * Get current or default locale.
     *
     * @return string
     */
    public function getCurrentOrDefaultLocale();
}
