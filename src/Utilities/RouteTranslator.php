<?php namespace Arcanedev\Localization\Utilities;

use Arcanedev\Localization\Contracts\RouteTranslatorInterface;
use Illuminate\Translation\Translator;

/**
 * Class     RouteTranslator
 *
 * @package  Arcanedev\Localization\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouteTranslator implements RouteTranslatorInterface
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
     * @param  Translator  $translator
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
     * @param  string  $currentRoute
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
     * @param  string  $route
     *
     * @return string
     */
    public function trans($route)
    {
        if ( ! in_array($route, $this->translatedRoutes)) {
            $this->translatedRoutes[] = $route;
        }

        return $this->translator->trans($route);
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
        $uri        = str_replace([url(), "/$locale/"], ['', ''], $uri);
        $uri        = trim($uri, '/');

        foreach ($this->translatedRoutes as $route) {
            $url = Url::substituteAttributes($attributes, $this->translator->trans($route));

            if ($url === $uri) return $route;
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
    public function findTranslatedRouteByPath($path, $urlLocale)
    {
        // check if this url is a translated url
        foreach ($this->translatedRoutes as $translatedRoute) {
            if ($this->translator->trans($translatedRoute, [], '', $urlLocale) == rawurldecode($path)) {
                return $translatedRoute;
            }
        }

        return false;
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
}
