<?php namespace Arcanedev\Localization\Middleware;

use Arcanedev\Localization\Bases\Middleware;
use Closure;
use Illuminate\Http\Request;

/**
 * Class     LocalizationRoutes
 *
 * @package  Arcanedev\Localization\Middleware
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocalizationRoutes extends Middleware
{
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
        localization()->setRouteNameFromRequest($request);

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
    private function getTranslatedUrl(Request $request)
    {
        /** @var \Illuminate\Routing\Route $route */
        $route = $request->route();

        if (
            ! ($route instanceof \Illuminate\Routing\Route) ||
            is_null($route->getName())
        ) {
            return null;
        }

        return $this->translateRoute(
            $route->getName(),
            $route->parameters()
        );
    }

    public function translateRoute($route, $attributes = [])
    {
        if (empty($attributes)) {
            return null;
        }

        $translatedAttributes = $this->fireEvent($attributes, $route);

        if (
            ! empty($translatedAttributes) &&
            $translatedAttributes !== $attributes
        ) {
            return route($route, $translatedAttributes);
        }

        return null;
    }

    private function fireEvent($attributes, $route)
    {
        $response   = event('routes.translation', [
            localization()->getCurrentLocale(), $attributes, $route
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
