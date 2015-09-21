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
        $locale = $request->segment(1, null);

        if ($this->localization->isLocaleSupported($locale)) {
            session()->put(compact('locale'));

            return $next($request);
        }

        $locale = session('locale', null);

        if (
            ! is_null($locale) &&
            ! $this->isDefaultLocaleHidden($locale)
        ) {
            session()->reflash();

            $redirect = $this->getLocalizedRedirect($locale);

            if ( ! is_null($redirect)) return $redirect;
        }

        return $next($request);
    }
}
