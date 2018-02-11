<?php namespace Arcanedev\Localization\Middleware;

use Arcanedev\Localization\Contracts\Localization;
use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class     Middleware
 *
 * @package  Arcanedev\Localization\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Middleware
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
        $this->except       = config('localization.ignored-uri', []);
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
    protected function shouldIgnore(Request $request)
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
        return is_string($localizedUrl = $this->localization->getLocalizedURL($locale))
            ? $this->makeRedirectResponse($localizedUrl)
            : null;
    }

    /**
     * Make a redirect response.
     *
     * @param  string    $url
     * @param  int|null  $code
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function makeRedirectResponse($url, $code = null)
    {
        return new RedirectResponse($url, $code ?? config('localization.redirection-code', 302), ['Vary' => 'Accept-Language']);
    }
}
