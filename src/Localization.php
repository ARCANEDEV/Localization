<?php namespace Arcanedev\Localization;

use Arcanedev\Localization\Contracts\LocalesManagerInterface;
use Arcanedev\Localization\Contracts\LocalizationInterface;
use Arcanedev\Localization\Contracts\RouteTranslatorInterface;
use Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException;
use Arcanedev\Localization\Exceptions\UnsupportedLocaleException;
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
     * Base url.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * The RouteTranslator instance.
     *
     * @var RouteTranslatorInterface
     */
    protected $routeTranslator;

    /**
     * The LocalesManager instance.
     *
     * @var LocalesManagerInterface
     */
    private $localesManager;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Creates new instance.
     *
     * @param  Application               $app
     * @param  RouteTranslatorInterface  $routeTranslator
     * @param  LocalesManagerInterface   $localesManager
     *
     * @throws UndefinedSupportedLocalesException
     * @throws UnsupportedLocaleException
     */
    public function __construct(
        Application              $app,
        RouteTranslatorInterface $routeTranslator,
        LocalesManagerInterface  $localesManager
    ) {
        $this->app              = $app;
        $this->routeTranslator  = $routeTranslator;
        $this->localesManager   = $localesManager;

        $this->localesManager->setDefaultLocale(
            $this->app['config']->get('app.locale')
        );
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
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
        return $this->localesManager->getDefaultLocale();
    }

    /**
     * Return an array of all supported Locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     *
     * @throws UndefinedSupportedLocalesException
     */
    public function getSupportedLocales()
    {
        return $this->localesManager->getSupportedLocales();
    }

    /**
     * Set the supported locales.
     *
     * @param  array  $supportedLocales
     *
     * @return self
     */
    public function setSupportedLocales(array $supportedLocales)
    {
        $this->localesManager->setSupportedLocales($supportedLocales);

        return $this;
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
        return $this->localesManager->getCurrentLocale();
    }

    /**
     * Returns current language.
     *
     * @return \Arcanedev\Localization\Entities\Locale
     */
    public function getCurrentLocaleEntity()
    {
        return $this->localesManager->getCurrentLocaleEntity();
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
     * Get all locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getAllLocales()
    {
        return $this->localesManager->getAllLocales();
    }

    /**
     * Set and return current locale.
     *
     * @param  string|null  $locale
     *
     * @return string
     */
    public function setLocale($locale = null)
    {
        return $this->localesManager->setLocale($locale);
    }

    /**
     * Sets the base url for the site.
     *
     * @param  string  $url
     *
     * @return self
     */
    public function setBaseUrl($url)
    {
        if (substr($url, -1) !== '/') {
            $url .= '/';
        }

        $this->baseUrl = $url;

        return $this;
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
     * @param  string|null  $url
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
     * @param  string|null  $url
     *
     * @return string
     */
    public function getNonLocalizedURL($url = null)
    {
        return $this->getLocalizedURL(false, $url);
    }

    /**
     * Returns an URL adapted to $locale or current locale.
     *
     * @todo: Refactor this beast
     *
     * @param  string|null  $locale
     * @param  string|null  $url
     * @param  array        $attributes
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
            $translatedRoute = $this->findTranslatedRouteByUrl($url, $attributes, $this->getCurrentLocale())
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
            ($locale !== $this->getDefaultLocale() || ! $this->isDefaultLocaleHiddenInUrl())
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
    private function findTranslatedRouteByUrl($url, $attributes, $locale)
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
     * @param  string  $locale
     * @param  string  $transKey
     * @param  array   $attributes
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
            ! ($locale === $this->getDefaultLocale() && $this->isDefaultLocaleHiddenInUrl())
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
        return $this->routeTranslator->getRouteNameFromPath($path, $this->getCurrentLocale());
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Hide the default locale in URL ??
     *
     * @return bool
     */
    public function isDefaultLocaleHiddenInUrl()
    {
        return $this->localesManager->isDefaultLocaleHiddenInUrl();
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
            $locale !== false && ! $this->localesManager->isSupportedLocale($locale)
        ) ? false : true;
    }

    /**
     * Check if the locale is supported or fail if not.
     *
     * @param  string  $locale
     *
     * @throws UnsupportedLocaleException
     */
    private function isLocaleSupportedOrFail($locale)
    {
        if ( ! $this->isLocaleSupported($locale)) {
            throw new UnsupportedLocaleException(
                "Locale '$locale' is not in the list of supported locales."
            );
        }
    }
}
