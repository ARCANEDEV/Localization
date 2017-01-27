<?php namespace Arcanedev\Localization\Middleware;

use Arcanedev\Localization\Bases\Middleware;
use Closure;
use Illuminate\Http\Request;

/**
 * Class     LocaleSessionRedirect
 *
 * @package  Arcanedev\Localization\Middleware
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @todo:    Refactoring
 */
class LocaleSessionRedirect extends Middleware
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
        $locale  = session('locale', null);

        if (localization()->isLocaleSupported($segment)) {
            session()->put(['locale' => $segment]);

            return $next($request);
        }
        elseif (localization()->isDefaultLocaleHiddenInUrl()) {
            $locale = localization()->getDefaultLocale();
            session()->put(compact('locale'));
        }

        if (is_string($locale) && ! $this->isDefaultLocaleHidden($locale)) {
            session()->reflash();

            $redirect = $this->getLocalizedRedirect($locale);

            if ( ! is_null($redirect)) return $redirect;
        }

        return $next($request);
    }
}
