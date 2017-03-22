<?php namespace Arcanedev\Localization\Middleware;

use Arcanedev\Localization\Contracts\Localization;
use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException;
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
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */
    /**
     * The localization instance.
     *
     * @var \Arcanedev\Localization\Contracts\Localization
     */
    protected $localization;

    /**
     * The URIs that should not be localized.
     *
     * @var array
     */
    protected $except = [];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */
    /**
     * Middleware constructor.
     *
     * @param  \Arcanedev\Localization\Contracts\Localization  $localization
     */
    public function __construct(Localization $localization)
    {
        $this->localization = $localization;
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
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

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
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

    /**
     * Determine if the request has a URI that should not be localized.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    protected function shouldIgnore($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/')
                $except = trim($except, '/');

            if ($request->is($except))
                return true;
        }

        return false;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */
    /**
     * Get the redirection response.
     *
     * @param  string  $locale
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    protected function getLocalizedRedirect($locale)
    {
        $localizedUrl = $this->localization->getLocalizedURL($locale);

        if ( ! is_string($localizedUrl)) return null;

        return $this->makeRedirectResponse($localizedUrl);
    }

    /**
     * Make a redirect response.
     *
     * @param  string  $url
     * @param  int     $code
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function makeRedirectResponse($url, $code = 302)
    {
        return new RedirectResponse($url, $code, ['Vary' => 'Accept-Language']);
    }
}
