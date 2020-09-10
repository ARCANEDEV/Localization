<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class     LocalizationRoutes
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocalizationRoutes extends Middleware
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
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
        if ($this->shouldIgnore($request)) return $next($request);

        $this->localization->setRouteNameFromRequest($request);

        return $next($request);
    }
}
