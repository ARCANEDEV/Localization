<?php namespace Arcanedev\Localization;

use Arcanedev\Localization\Contracts\LocalizationInterface;
use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException;
use Arcanedev\Localization\Exceptions\UnsupportedLocaleException;
use Arcanedev\Localization\Utilities\LocaleNegotiator;
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
    protected $supportedLocales;

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
     * Translated routes collection.
     *
     * @var array
     */
    protected $translatedRoutes = [];

    /**
     * Current route name.
     *
     * @var string
     */
    protected $routeName;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Creates new instance.
     *
     * @param  Application  $app
     *
     * @throws UndefinedSupportedLocalesException
     * @throws UnsupportedLocaleException
     */
    public function __construct(Application $app)
    {
        $this->app              = $app;
        $this->supportedLocales = new LocaleCollection;
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
     * Get the translator instance.
     *
     * @return \Illuminate\Translation\Translator
     */
    private function translator()
    {
        return $this->app['translator'];
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
     * @throws UndefinedSupportedLocalesException
     *
     * @return LocaleCollection
     */
    public function getSupportedLocales()
    {
        if ( ! $this->supportedLocales->isEmpty()) {
            return $this->supportedLocales;
        }

        $locales = $this->config()->get('localization.supported-locales');

        if ( ! is_array($locales) || empty($locales)) {
            throw new UndefinedSupportedLocalesException;
        }

        return $this->supportedLocales->loadSupported();
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
        return $this->supportedLocales->keys()->toArray();
    }

    /**
     * Returns current locale name.
     *
     * @todo: Refactor to Locale Entity
     *
     * @return string
     */
    public function getCurrentLocaleName()
    {
        /** @var Entities\Locale $locale */
        $locale = $this->supportedLocales->get($this->getCurrentLocale());

        return $locale->name();
    }

    /**
     * Returns current locale script.
     *
     * @todo: Refactor to Locale Entity
     *
     * @return string
     */
    public function getCurrentLocaleScript()
    {
        /** @var Entities\Locale $locale */
        $locale = $this->supportedLocales->get($this->getCurrentLocale());

        return $locale->script();
    }

    /**
     * Returns current locale direction.
     *
     * @todo: Refactor to Locale Entity
     *
     * @return string
     */
    public function getCurrentLocaleDirection()
    {
        /** @var Entities\Locale $locale */
        $locale = $this->supportedLocales->get($this->getCurrentLocale());

        return $locale->direction();
    }

    /**
     * Returns current locale native name.
     *
     * @todo: Refactor to Locale Entity
     *
     * @return string
     */
    public function getCurrentLocaleNative()
    {
        /** @var Entities\Locale $locale */
        $locale = $this->supportedLocales->get($this->getCurrentLocale());

        return $locale->native();
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

        if ($this->supportedLocales->has($locale)) {
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
     * Returns current language.
     *
     * @todo:  Refactor to Locale entity
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

        $negotiator = new LocaleNegotiator(
            $this->defaultLocale,
            $this->getSupportedLocales(),
            $this->request()
        );

        return $negotiator->negotiate();
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
     * @param  string  $routeName
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
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
        if ( ! in_array($routeName, $this->translatedRoutes)) {
            $this->translatedRoutes[] = $routeName;
        }

        return $this->translator()->trans($routeName);
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
     * @param  string|bool   $locale
     * @param  string|false  $url
     * @param  array         $attributes
     *
     * @throws UndefinedSupportedLocalesException
     * @throws UnsupportedLocaleException
     *
     * @return string|false
     */
    public function getLocalizedURL($locale = null, $url = null, $attributes = [])
    {
        if ($locale === null) {
            $locale = $this->getCurrentLocale();
        }

        if ( ! $this->isLocaleSupported($locale)) {
            throw new UnsupportedLocaleException(
                "Locale '$locale' is not in the list of supported locales."
            );
        }

        if (empty($attributes)) {
            $attributes = Url::extractAttributes($url);
        }

        if (empty($url)) {
            if ( ! empty($this->routeName)) {
                return $this->getURLFromRouteNameTranslated($locale, $this->routeName, $attributes);
            }

            $url = $this->request()->fullUrl();
        }

        if (
            $locale &&
            $translatedRoute = $this->findTranslatedRouteByUrl($url, $attributes, $this->currentLocale)
        ) {
            return $this->getURLFromRouteNameTranslated($locale, $translatedRoute, $attributes);
        }

        $base_path  = $this->request()->getBaseUrl();
        $parsedUrl = parse_url($url);
        $url_locale = $this->getDefaultLocale();

        if ( ! $parsedUrl || empty($parsedUrl['path'])) {
            $path = $parsedUrl['path'] = '';
        }
        else {
            $path = $parsedUrl['path'] = str_replace($base_path, '', '/' . ltrim($parsedUrl['path'], '/'));

            foreach ($this->getSupportedLocales() as $localeCode => $lang) {
                $parsedUrl['path'] = preg_replace('%^/?' . $localeCode . '/%', '$1', $parsedUrl[ 'path' ]);

                if ($parsedUrl['path'] !== $path) {
                    $url_locale = $localeCode;
                    break;
                }

                $parsedUrl['path'] = preg_replace('%^/?' . $localeCode . '$%', '$1', $parsedUrl['path']);

                if ($parsedUrl['path'] !== $path) {
                    $url_locale = $localeCode;
                    break;
                }
            }
        }

        $parsedUrl['path'] = ltrim($parsedUrl['path'], '/');

        if ($translatedRoute = $this->findTranslatedRouteByPath($parsedUrl['path'], $url_locale)) {
            return $this->getURLFromRouteNameTranslated($locale, $translatedRoute, $attributes);
        }

        if (
            ! empty($locale) &&
            ($locale != $this->defaultLocale || ! $this->hideDefaultLocaleInURL())
        ) {
            $parsedUrl['path'] = $locale . '/' . ltrim($parsedUrl['path'], '/');
        }

        $parsedUrl['path'] = ltrim(ltrim($base_path, '/') . '/' . $parsedUrl['path'], '/');

        //Make sure that the pass path is returned with a leading slash only if it come in with one.
        if (starts_with($path, '/') === true) {
            $parsedUrl['path'] = '/' . $parsedUrl['path'];
        }

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
     * @throws UndefinedSupportedLocalesException
     * @throws UnsupportedLocaleException
     *
     * @return string|false
     */
    protected function findTranslatedRouteByUrl($url, $attributes, $locale)
    {
        if (empty($url)) {
            return false;
        }

        // check if this url is a translated url
        foreach ($this->translatedRoutes as $translatedRoute) {
            $routeName = $this->getURLFromRouteNameTranslated($locale, $translatedRoute, $attributes);

            if ($this->getNonLocalizedURL($routeName) == $this->getNonLocalizedURL($url))  {
                return $translatedRoute;
            }
        }

        return false;
    }

    /**
     * Returns the translated route for the path and the url given.
     *
     * @param  string  $path       -  Path to check if it is a translated route
     * @param  string  $urlLocale  -  Language to check if the path exists
     *
     * @return string|false
     */
    protected function findTranslatedRouteByPath($path, $urlLocale)
    {
        // check if this url is a translated url
        foreach ($this->translatedRoutes as $translatedRoute) {
            if ($this->translator()->trans($translatedRoute, [], '', $urlLocale) == rawurldecode($path)) {
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
     * @throws UndefinedSupportedLocalesException
     * @throws UnsupportedLocaleException
     *
     * @return string|false
     */
    public function getURLFromRouteNameTranslated($locale, $transKey, $attributes = [])
    {
        if ( ! $this->isLocaleSupported($locale)) {
            throw new UnsupportedLocaleException(
                "Locale '$locale' is not in the list of supported locales."
            );
        }

        if ( ! is_string($locale)) {
            $locale = $this->getDefaultLocale();
        }

        $route = '';

        if ( ! ($locale === $this->defaultLocale && $this->hideDefaultLocaleInURL())) {
            $route = '/' . $locale;
        }

        if (
            is_string($locale) &&
            $this->translator()->has($transKey, $locale)
        ) {
            $translation = $this->translator()->trans($transKey, [ ], '', $locale);
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
    public function getRouteNameFromAPath($path)
    {
        $attributes = Url::extractAttributes($path);
        $path       = str_replace(url(), '', $path);

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        $path = str_replace('/' . $this->currentLocale . '/', '', $path);
        $path = trim($path, '/');

        foreach ($this->translatedRoutes as $route) {
            if (Url::substituteAttributes($attributes, $this->translator()->trans($route)) === $path) {
                return $route;
            }
        }

        return false;
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
        if ($this->supportedLocales->has($this->defaultLocale)) {
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
}
