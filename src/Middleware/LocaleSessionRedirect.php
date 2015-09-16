<?php namespace Arcanedev\Localization\Middleware;

use Arcanedev\Localization\Bases\Middleware;
use Closure;
use Illuminate\Http\RedirectResponse;
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
        $params = explode('/', $request->path());

        if (
            count($params) > 0 && $locale = $this->localization->isLocaleSupported($params[0])
        ) {
            session(['locale' => $params[0]]);

            return $next($request);
        }

        $locale = session('locale', false);

        if ($locale && ! $this->isDefaultLocaleHidden($locale)) {
            app('session')->reflash();

            $redirection = $this->localization->getLocalizedURL($locale);

            if (is_string($redirection)) {
                return new RedirectResponse($redirection, 302, [
                    'Vary' => 'Accept-Language'
                ]);
            }
        }

        return $next($request);
    }
}
