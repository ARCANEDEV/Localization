<?php namespace Arcanedev\Localization\Middleware;

use Arcanedev\Localization\Bases\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

/**
 * Class     TranslationRedirect
 *
 * @package  Arcanedev\Localization\Middleware
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TranslationRedirect extends Middleware
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const TRANSLATION_EVENT = 'routes.translation';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $translatedUrl = $this->getTranslatedUrl($request);

        if ( ! is_null($translatedUrl)) {
            return $this->makeRedirectResponse($translatedUrl, 301);
        }

        return $next($request);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get translated URL.
     *
     * @param  Request  $request
     *
     * @return null|string
     */
    private function getTranslatedUrl(Request $request)
    {
        /** @var Route $route */
        $route = $request->route();

        if ( ! ($route instanceof Route) || is_null($route->getName())) {
            return null;
        }

        return $this->translateRoute(
            $route->getName(), $route->parameters()
        );
    }

    /**
     * Translate route.
     *
     * @param  string  $routeName
     * @param  array   $attributes
     *
     * @return Route|null
     */
    public function translateRoute($routeName, $attributes = [])
    {
        if (empty($attributes)) {
            return null;
        }

        $translatedAttributes = $this->fireEvent($attributes, $routeName);

        if (
            ! empty($translatedAttributes) &&
            $translatedAttributes !== $attributes
        ) {
            return route($routeName, $translatedAttributes);
        }

        return null;
    }

    /**
     * Fire translation event.
     *
     * @param  string  $route
     * @param  array   $attributes
     *
     * @return array
     */
    private function fireEvent($route, $attributes)
    {
        $response = event(self::TRANSLATION_EVENT, [
            $route, $attributes, $this->getCurrentLocale()
        ]);

        if ( ! empty($response)) {
            $response = array_shift($response);
        }

        if (is_array($response)) {
            $attributes = array_merge($attributes, $response);
        }

        return $attributes;
    }
}
