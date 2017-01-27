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
    const EVENT_NAME = 'routes.translation';

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
        // If the request URL is ignored from localization.
        if ($this->shouldIgnore($request))
            return $next($request);

        $translatedUrl = $this->getTranslatedUrl($request);

        if ( ! is_null($translatedUrl)) {
            return $this->makeRedirectResponse($translatedUrl);
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

        $transAttributes = $this->fireEvent(
            $this->getCurrentLocale(), $routeName, $attributes
        );

        if (
            ! empty($transAttributes) && $transAttributes !== $attributes
        ) {
            return route($routeName, $transAttributes);
        }

        return null;
    }

    /**
     * Fire translation event.
     *
     * @param  string  $locale
     * @param  string  $route
     * @param  array   $attributes
     *
     * @return array
     */
    private function fireEvent($locale, $route, $attributes)
    {
        $response = event(self::EVENT_NAME, [
            $locale, $route, $attributes
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
