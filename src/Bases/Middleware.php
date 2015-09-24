<?php namespace Arcanedev\Localization\Bases;

use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException;
use Arcanedev\Localization\Localization;
use Arcanedev\Support\Bases\Middleware as BaseMiddleware;
use Illuminate\Http\RedirectResponse;

/**
 * Class     Middleware
 *
 * @package  Arcanedev\Localization\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Middleware extends BaseMiddleware
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The localization instance.
     *
     * @var Localization
     */
    protected $localization;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct()
    {
        $this->localization = localization();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the default locale.
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->localization->getDefaultLocale();
    }

    /**
     * Get the current locale.
     *
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->localization->getCurrentLocale();
    }

    /**
     * Return an array of all supported Locales.
     *
     * @throws UndefinedSupportedLocalesException
     *
     * @return LocaleCollection
     */
    public function getSupportedLocales()
    {
        return $this->localization->getSupportedLocales();
    }

    /**
     * Hide the default locale in URL ??
     *
     * @return bool
     */
    protected function hideDefaultLocaleInURL()
    {
        return $this->localization->isDefaultLocaleHiddenInUrl();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check is default locale hidden.
     *
     * @param  string|null  $locale
     *
     * @return bool
     */
    protected function isDefaultLocaleHidden($locale)
    {
        return $this->getDefaultLocale() === $locale && $this->hideDefaultLocaleInURL();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the redirection response.
     *
     * @param  string  $locale
     *
     * @return RedirectResponse|null
     */
    protected function getLocalizedRedirect($locale)
    {
        $localizedUrl = localization()->getLocalizedURL($locale);

        if ( ! is_string($localizedUrl)) return null;

        return $this->makeRedirectResponse($localizedUrl);
    }

    /**
     * Make a redirect response.
     *
     * @param  string  $url
     * @param  int     $code
     *
     * @return RedirectResponse
     */
    protected function makeRedirectResponse($url, $code = 302)
    {
        return new RedirectResponse($url, $code, [
            'Vary' => 'Accept-Language'
        ]);
    }
}
