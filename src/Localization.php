<?php namespace Arcanedev\Localization;

use Arcanedev\Localization\Contracts\LocalizationInterface;
use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException;
use Arcanedev\Localization\Exceptions\UnsupportedLocaleException;
use Arcanedev\Localization\Utilities\LocalesManager;
use Arcanedev\Localization\Utilities\Negotiator;
use Arcanedev\Localization\Utilities\RouteTranslator;
use Arcanedev\Localization\Utilities\Url;
use Illuminate\Foundation\Application;

/**
 * Class     Localization
 *
 * @package  Arcanedev\Localization
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Localization implements LocalizationInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Laravel application instance.
     *
     * @var Application
     */
    private $app;

    /**
     * Default locale.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * Supported Locales.
     *
     * @var Entities\LocaleCollection
     */
    protected $locales;

    /**
     * Current locale.
     *
     * @var string|null
     */
    protected $currentLocale = null;

    /**
     * Base url.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * The RouteTranslator instance.
     *
     * @var RouteTranslator
     */
    protected $routeTranslator;

    /**
     * The LocalesManager instance.
     *
     * @var LocalesManager
     */
    private $localesManager;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Creates new instance.
     *
     * @param  Application      $app
     * @param  RouteTranslator  $routeTranslator
     * @param  LocalesManager   $localesManager
     *
     * @throws UndefinedSupportedLocalesException
     * @throws UnsupportedLocaleException
     */
    public function __construct(Application $app, $routeTranslator, $localesManager)
    {
        $this->app              = $app;
        $this->routeTranslator  = $routeTranslator;
        $this->localesManager   = $localesManager;

        $this->locales          = new LocaleCollection;
        $this->defaultLocale    = $this->config()->get('app.locale');

        $this->getSupportedLocales();
        $this->isDefaultLocaleSupported();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Config repository.
     *
     * @return \Illuminate\Config\Repository
     */
    public function config()
    {
        return $this->app['config'];
    }

    /**
     * Get Request instance.
     *
     * @return \Illuminate\Http\Request
     */
    private function request()
    {
        return $this->app['request'];
    }

    /**
     * Returns default locale.
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * Return an array of all supported Locales.
     *
     * @return LocaleCollection
     *
     * @throws UndefinedSupportedLocalesException
     */
    public function getSupportedLocales()
    {
        return $this->localesManager->getSupportedLocales();
    }

    /**
     * Get supported locales keys.
     *
     * @return array
     *
     * @throws UndefinedSupportedLocalesException
     */
    public function getSupportedLocalesKeys()
    {
        return $this->localesManager->getSupportedLocalesKeys();
    }

    /**
     * Returns current language.
     *
     * @return string
     */
    public function getCurrentLocale()
    {
        if ( ! is_null($this->currentLocale)) {
            return $this->currentLocale;
        }

        // Get application default language
        if ( ! $this->useAcceptLanguageHeader()) {
            return $this->config()->get('app.locale');
        }

        $negotiator = new Negotiator(
            $this->defaultLocale,
            $this->getSupportedLocales()
        );

        return $negotiator->negotiate($this->request());
    }

    /**
     * Returns current language.
     *
     * @return Entities\Locale
     */
    public function getCurrentLocaleEntity()
    {
        return $this->getSupportedLocales()->get($this->getCurrentLocale());
    }

    /**
     * Returns current locale name.
     *
     * @return string
     */
    public function getCurrentLocaleName()
    {
        return $this->getCurrentLocaleEntity()->name();
    }

    /**
     * Returns current locale script.
     *
     * @return string
     */
    public function getCurrentLocaleScript()
    {
        return $this->getCurrentLocaleEntity()->script();
    }

    /**
     * Returns current locale direction.
     *
     * @return string
     */
    public function getCurrentLocaleDirection()
    {
        return $this->getCurrentLocaleEntity()->direction();
    }

    /**
     * Returns current locale native name.
     *
     * @return string
     */
    public function getCurrentLocaleNative()
    {
        return $this->getCurrentLocaleEntity()->native();
    }

    /**
     * Set and return current locale.
     *
     * @param  string  $locale
     *
     * @return string
     */
    public function setLocale($locale = null)
    {
        if (empty($locale) || ! is_string($locale)) {
            // If the locale has not been passed through the function
            // it tries to get it from the first segment of the url
            $locale = $this->request()->segment(1);
        }

        if ($this->getSupportedLocales()->has($locale)) {
            $this->currentLocale = $locale;
        }
        else {
            // if the first segment/locale passed is not valid the system would ask which locale have to take
            // it could be taken by the browser depending on your configuration
            $locale = null;


            $this->currentLocale = $this->hideDefaultLocaleInURL()
                // if we reached this point and hideDefaultLocaleInURL is true we have to assume we are routing
                // to a defaultLocale route.
                ? $this->defaultLocale
                // but if hideDefaultLocaleInURL is false, we have to retrieve it from the browser...
                : $this->getCurrentLocale();
        }

        $this->app->setLocale($this->currentLocale);

        return $locale;
    }

    /**
     * Sets the base url for the site.
     *
     * @param  string  $url
     */
    public function setBaseUrl($url)
    {
        if (substr($url, -1) !== '/') {
            $url .= '/';
        }

        $this->baseUrl = $url;
    }

    /**
     * Set current route name.
     *
     * @param  false|string  $routeName
     */
    public function setRouteName($routeName)
    {
        $this->routeTranslator->setCurrentRoute($routeName);
    }

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
    public function transRoute($routeName)
    {
        return $this->routeTranslator->trans($routeName);
    }

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
    public function localizeURL($url = null, $locale = null)
    {
        return $this->getLocalizedURL($locale, $url);
    }

    /**
     * It returns an URL without locale (if it has it).
     *
     * @param  string|false  $url
     *
     * @return string
     */
    public function getNonLocalizedURL($url = null)
    {
        return $this->getLocalizedURL(false, $url);
    }

    /**
     * Returns an URL adapted to $locale.
     *
     * @todo: Refactor this beast
     *
     * @param  string|bool   $locale
     * @param  string|false  $url
     * @param  array         $attributes
     *
     * @return string|false
     *
     * @throws UndefinedSupportedLocalesException
     * @throws UnsupportedLocaleException
     */
    public function getLocalizedURL($locale = null, $url = null, $attributes = [])
    {
        if ($locale === null) {
            $locale = $this->getCurrentLocale();
        }

        $this->isLocaleSupportedOrFail($locale);

        if (empty($attributes)) {
            $attributes = Url::extractAttributes($url);
        }

        if (empty($url)) {
            if ($this->routeTranslator->hasCurrentRoute()) {
                return $this->getUrlFromRouteName(
                    $locale,
                    $this->routeTranslator->getCurrentRoute(),
                    $attributes
                );
            }

            $url = $this->request()->fullUrl();
        }

        if (
            $locale &&
            $translatedRoute = $this->findTranslatedRouteByUrl($url, $attributes, $this->currentLocale)
        ) {
            return $this->getUrlFromRouteName($locale, $translatedRoute, $attributes);
        }

        $baseUrl       = $this->request()->getBaseUrl();
        $parsedUrl     = parse_url($url);
        $defaultLocale = $this->getDefaultLocale();

        if ( ! $parsedUrl || empty($parsedUrl['path'])) {
            $parsedUrl['path'] = '';
        }
        else {
            $path = $parsedUrl['path'] = str_replace($baseUrl, '', '/' . ltrim($parsedUrl['path'], '/'));

            foreach ($this->getSupportedLocales() as $localeCode => $lang) {
                $parsedUrl['path'] = preg_replace('%^/?' . $localeCode . '/%', '$1', $parsedUrl[ 'path' ]);

                if ($parsedUrl['path'] !== $path) {
                    $defaultLocale = $localeCode;
                    break;
                }

                $parsedUrl['path'] = preg_replace('%^/?' . $localeCode . '$%', '$1', $parsedUrl['path']);

                if ($parsedUrl['path'] !== $path) {
                    $defaultLocale = $localeCode;
                    break;
                }
            }
        }

        $parsedUrl['path'] = ltrim($parsedUrl['path'], '/');

        $translatedRoute = $this->routeTranslator->findTranslatedRouteByPath($parsedUrl['path'], $defaultLocale);

        if ($translatedRoute) {
            return $this->getUrlFromRouteName($locale, $translatedRoute, $attributes);
        }

        if (
            ! empty($locale) &&
            ($locale !== $this->defaultLocale || ! $this->hideDefaultLocaleInURL())
        ) {
            $parsedUrl['path'] = $locale . '/' . ltrim($parsedUrl['path'], '/');
        }

        $parsedUrl['path'] = ltrim(ltrim($baseUrl, '/') . '/' . $parsedUrl['path'], '/');
        $parsedUrl['path'] = rtrim($parsedUrl['path'], '/');

        $url = Url::unparse($parsedUrl);

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        return $this->createUrlFromUri($url);
    }

    /**
     * Create an url from the uri.
     *
     * @param  string  $uri
     *
     * @return string
     */
    public function createUrlFromUri($uri)
    {
        $uri = ltrim($uri, '/');

        if (empty($this->baseUrl)) {
            return app('url')->to($uri);
        }

        return $this->baseUrl . $uri;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Translation Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Returns the translated route for an url and the attributes given and a locale
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  string  $locale
     *
     * @return string|false
     *
     * @throws UndefinedSupportedLocalesException
     * @throws UnsupportedLocaleException
     */
    protected function findTranslatedRouteByUrl($url, $attributes, $locale)
    {
        if (empty($url)) return false;

        // check if this url is a translated url
        foreach ($this->routeTranslator->getTranslatedRoutes() as $translatedRoute) {
            $routeName = $this->getUrlFromRouteName($locale, $translatedRoute, $attributes);

            if ($this->getNonLocalizedURL($routeName) == $this->getNonLocalizedURL($url))  {
                return $translatedRoute;
            }
        }

        return false;
    }

    /**
     * Returns an URL adapted to the route name and the locale given.
     *
     * @param  string|bool  $locale
     * @param  string       $transKey
     * @param  array        $attributes
     *
     * @return string|false
     *
     * @throws UndefinedSupportedLocalesException
     * @throws UnsupportedLocaleException
     */
    public function getUrlFromRouteName($locale, $transKey, $attributes = [])
    {
        $this->isLocaleSupportedOrFail($locale);

        if ( ! is_string($locale)) {
            $locale = $this->getDefaultLocale();
        }

        $route = '';

        if (
            ! ($locale === $this->defaultLocale && $this->hideDefaultLocaleInURL())
        ) {
            $route = '/' . $locale;
        }

        if (
            is_string($locale) &&
            $this->routeTranslator->hasTranslation($transKey, $locale)
        ) {
            $translation = $this->routeTranslator->trans($transKey, $locale);
            $route       = Url::substituteAttributes($attributes, $route . '/' . $translation);
        }

        // This locale does not have any key for this route name
        if (empty($route)) return false;

        return rtrim($this->createUrlFromUri($route));
    }

    /**
     * Returns the translation key for a given path.
     *
     * @param  string  $path
     *
     * @return string|false
     */
    public function getRouteNameFromPath($path)
    {
        return $this->routeTranslator->getRouteNameFromPath($path, $this->currentLocale);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if default is supported.
     *
     * @throws UnsupportedLocaleException
     */
    private function isDefaultLocaleSupported()
    {
        if ($this->getSupportedLocales()->has($this->defaultLocale)) {
            return;
        }

        throw new UnsupportedLocaleException(
            "Laravel default locale [{$this->defaultLocale}] is not in the `supported-locales` array."
        );
    }

    /**
     * Check if Locale exists on the supported locales collection.
     *
     * @param  string|bool  $locale
     *
     * @throws UndefinedSupportedLocalesException
     *
     * @return bool
     */
    public function isLocaleSupported($locale)
    {
        return (
            $locale !== false &&
            ! $this->getSupportedLocales()->has($locale)
        ) ? false : true;
    }

    /**
     * Check if the locale is supported or fail if not.
     *
     * @param  string  $locale
     *
     * @throws UnsupportedLocaleException
     */
    public function isLocaleSupportedOrFail($locale)
    {
        if ($this->isLocaleSupported($locale)) {
            return;
        }

        throw new UnsupportedLocaleException(
            "Locale '$locale' is not in the list of supported locales."
        );
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Hide the default locale in URL ??
     *
     * @return bool
     */
    public function hideDefaultLocaleInURL()
    {
        return (bool) $this->config()->get('localization.hide-default-in-url', false);
    }

    /**
     * Returns the translation key for a given path.
     *
     * @return bool
     */
    private function useAcceptLanguageHeader()
    {
        return (bool) $this->config()->get('localization.accept-language-header', true);
    }

    /**
     * Get locales navigation bar.
     *
     * @return string
     */
    public function localesNavbar()
    {
        $supportedLocales = $this->getSupportedLocales();

        return view('localization::navbar', compact('supportedLocales'))->render();
    }
}
