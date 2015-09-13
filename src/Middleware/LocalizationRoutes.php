<?php namespace Arcanedev\Localization\Middleware;

use Closure;
use Arcanedev\Localization\Bases\Middleware;
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
        $routeName = localization()->getRouteNameFromAPath($request->getUri());

        localization()->setRouteName($routeName);

        return $next($request);
    }
}
