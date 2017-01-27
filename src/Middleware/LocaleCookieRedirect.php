<?php namespace Arcanedev\Localization\Middleware;

use Arcanedev\Localization\Bases\Middleware;
use Closure;
use Illuminate\Http\Request;

/**
 * Class     LocaleCookieRedirect
 *
 * @package  Arcanedev\Localization\Middleware
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @todo:    Refactoring
 */
class LocaleCookieRedirect extends Middleware
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
        // If the request URL is ignored from localization.
        if ($this->shouldIgnore($request))
            return $next($request);

        $segment = $request->segment(1, null);
        $locale  = $request->cookie('locale', null);

        if (localization()->isLocaleSupported($segment)) {
            return $next($request)->withCookie(cookie()->forever('locale', $segment));
        }

        if ($locale !== null && ! $this->isDefaultLocaleHidden($locale)) {
            if ( ! is_null($redirect = $this->getLocalizedRedirect($locale))) {
                return $redirect->withCookie(cookie()->forever('locale', $segment));
            }
        }

        return $next($request);
    }
}
