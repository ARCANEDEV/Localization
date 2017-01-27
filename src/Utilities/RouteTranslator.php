<?php namespace Arcanedev\Localization\Utilities;

use Arcanedev\Localization\Contracts\RouteTranslator as RouteTranslatorContract;
use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Exceptions\InvalidTranslationException;
use Illuminate\Translation\Translator;

/**
 * Class     RouteTranslator
 *
 * @package  Arcanedev\Localization\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouteTranslator implements RouteTranslatorContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The translator instance.
     *
     * @var \Illuminate\Translation\Translator
     */
    private $translator;

    /**
     * Current route.
     *
     * @var string
     */
    protected $currentRoute = '';

    /**
     * Translated routes collection.
     *
     * @var array
     */
    protected $translatedRoutes = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create RouteTranslator instance.
     *
     * @param  \Illuminate\Translation\Translator  $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get current route.
     *
     * @return string
     */
    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }

    /**
     * Set the current route.
     *
     * @param  false|string  $currentRoute
     *
     * @return self
     */
    public function setCurrentRoute($currentRoute)
    {
        if (is_string($currentRoute)) {
            $this->currentRoute = $currentRoute;
        }

        return $this;
    }

    /**
     * Get translated routes.
     *
     * @return array
     */
    public function getTranslatedRoutes()
    {
        return $this->translatedRoutes;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Translate routes and save them to the translated routes array (used in the localize route filter).
     *
     * @param  string       $route
     * @param  string|null  $locale
     *
     * @return string
     */
    public function trans($route, $locale = null)
    {
        if ( ! in_array($route, $this->translatedRoutes)) {
            $this->translatedRoutes[] = $route;
        }

        return $this->translate($route, $locale);
    }

    /**
     * Get the translated route.
     *
     * @param  string                                             $baseUrl
     * @param  array|false                                        $parsedUrl
     * @param  string                                             $defaultLocale
     * @param  \Arcanedev\Localization\Entities\LocaleCollection  $supportedLocales
     *
     * @return string|false
     */
    public function getTranslatedRoute(
        $baseUrl, &$parsedUrl, $defaultLocale, LocaleCollection $supportedLocales
    ) {
        if (empty($parsedUrl) || ! isset($parsedUrl['path'])) {
            $parsedUrl['path'] = '';
        }
        else {
            $path = $parsedUrl['path'] = str_replace($baseUrl, '', '/' . ltrim($parsedUrl['path'], '/'));

            foreach ($supportedLocales->keys() as $locale) {
                foreach (["%^/?$locale/%", "%^/?$locale$%"] as $pattern) {
                    $parsedUrl['path'] = preg_replace($pattern, '$1', $parsedUrl['path']);

                    if ($parsedUrl['path'] !== $path) {
                        $defaultLocale = $locale;
                        break 2;
                    }
                }
            }
        }

        $parsedUrl['path'] = ltrim($parsedUrl['path'], '/');

        return $this->findTranslatedRouteByPath($parsedUrl['path'], $defaultLocale);
    }

    /**
     * Returns the translation key for a given path.
     *
     * @param  string  $uri
     * @param  string  $locale
     *
     * @return false|string
     */
    public function getRouteNameFromPath($uri, $locale)
    {
        $attributes = Url::extractAttributes($uri);
        $uri        = str_replace([url('/'), "/$locale/"], '', $uri);
        $uri        = trim($uri, '/');

        foreach ($this->translatedRoutes as $routeName) {
            $url = Url::substituteAttributes($attributes, $this->translate($routeName));

            if ($url === $uri) {
                return $routeName;
            }
        }

        return false;
    }

    /**
     * Returns the translated route for the path and the url given.
     *
     * @param  string  $path    -  Path to check if it is a translated route
     * @param  string  $locale  -  Language to check if the path exists
     *
     * @return false|string
     */
    public function findTranslatedRouteByPath($path, $locale)
    {
        // check if this url is a translated url
        foreach ($this->translatedRoutes as $route) {
            if ($this->translate($route, $locale) == rawurldecode($path)) {
                return $route;
            }
        }

        return false;
    }

    /**
     * Get URL from route name.
     *
     * @param  string      $locale
     * @param  string      $defaultLocale
     * @param  string      $transKey
     * @param  array       $attributes
     * @param  bool|false  $defaultHidden
     *
     * @return string
     */
    public function getUrlFromRouteName($locale, $defaultLocale, $transKey, $attributes = [], $defaultHidden = false)
    {
        if ( ! is_string($locale)) {
            $locale = $defaultLocale;
        }

        $route = '';

        if ( ! ($locale === $defaultLocale && $defaultHidden)) {
            $route = '/' . $locale;
        }

        if ($this->hasTranslation($transKey, $locale)) {
            $translation = $this->trans($transKey, $locale);
            $route       = Url::substituteAttributes($attributes, $route . '/' . $translation);
        }

        return $route;
    }

    /**
     * Get the translation for a given key.
     *
     * @param  string  $key
     * @param  string  $locale
     *
     * @return string
     *
     * @throws \Arcanedev\Localization\Exceptions\InvalidTranslationException
     */
    private function translate($key, $locale = null)
    {
        if (is_null($locale)) {
            $locale = $this->translator->getLocale();
        }

        $translation = $this->translator->trans($key, [], $locale);

        // @codeCoverageIgnoreStart
        if ( ! is_string($translation)) {
            throw new InvalidTranslationException(
                "The translation key [$key] for locale [$locale] should return a string value."
            );
        }
        // @codeCoverageIgnoreEnd

        return (string) $translation;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if has current route.
     *
     * @return bool
     */
    public function hasCurrentRoute()
    {
        return ! empty($this->currentRoute);
    }

    /**
     * Determine if a translation exists.
     *
     * @param  string  $key
     * @param  string  $locale
     *
     * @return bool
     */
    public function hasTranslation($key, $locale = null)
    {
        return $this->translator->has($key, $locale);
    }
}
