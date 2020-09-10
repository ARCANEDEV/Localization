<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class     LocaleSessionRedirect
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleSessionRedirect extends Middleware
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

        $segment = $request->segment(1, null);
        $locale  = session('locale', null);

        if ($this->localization->isLocaleSupported($segment)) {
            session()->put(['locale' => $segment]);

            return $next($request);
        }
        elseif ($this->localization->isDefaultLocaleHiddenInUrl()) {
            $locale = $this->localization->getDefaultLocale();
            session()->put(compact('locale'));
        }

        if (is_string($locale) && ! $this->isDefaultLocaleHidden($locale)) {
            session()->reflash();

            if ( ! is_null($redirect = $this->getLocalizedRedirect($locale)))
                return $redirect;
        }

        return $next($request);
    }
}
