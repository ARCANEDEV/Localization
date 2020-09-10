<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Middleware;

use Arcanedev\Localization\Contracts\Localization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class     Middleware
 *
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
     * The URIs or route names that should not be localized.
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
        $this->except       = $this->getIgnoredRedirection();
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
     * @return \Arcanedev\Localization\Entities\LocaleCollection
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

    /**
     * Get the ignored URI/Route.
     *
     * @return array
     */
    protected function getIgnoredRedirection(): array
    {
        return config('localization.ignored-redirection', []);
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
    protected function shouldIgnore(Request $request): bool
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }

            if ($request->routeIs($except)) {
                return true;
            }
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
