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
        $segment = $request->segment(1, null);
        $locale  = $request->cookie('locale', null);

        if (localization()->isLocaleSupported($segment)) {
            cookie('locale', $segment);

            return $next($request);
        }
        elseif (localization()->isDefaultLocaleHiddenInUrl()) {
            $locale = localization()->getDefaultLocale();
            cookie('locale', $locale);
        }

        if (is_string($locale) && ! $this->isDefaultLocaleHidden($locale)) {
            session()->reflash();

            $redirect = $this->getLocalizedRedirect($locale);

            if ( ! is_null($redirect)) return $redirect;
        }

        return $next($request);
    }
}
