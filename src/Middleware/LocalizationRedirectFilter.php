<?php namespace Arcanedev\Localization\Middleware;

use Arcanedev\Localization\Bases\Middleware;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class     LocalizationRedirectFilter
 *
 * @package  Arcanedev\Localization\Middleware
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @todo:    Refactoring
 */
class LocalizationRedirectFilter extends Middleware
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

        if (count($params) > 0) {
            $localeCode  = $params[0];

            if ($redirection = $this->getRedirection($localeCode)) {
                // Save any flashed data for redirect
                app('session')->reflash();

                return new RedirectResponse($redirection, 301, [
                    'Vary' => 'Accept-Language'
                ]);
            }
        }

        return $next($request);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get redirection.
     *
     * @param  string  $localeCode
     *
     * @return bool
     */
    private function getRedirection($localeCode)
    {
        $currentLocale     = localization()->getCurrentLocale();
        $defaultLocale     = localization()->getDefaultLocale();
        $supportedLocales  = localization()->getSupportedLocales();
        $hideDefaultLocale = localization()->hideDefaultLocaleInURL();
        $redirection       = false;

        if ($supportedLocales->has($localeCode)) {
            if ($localeCode === $defaultLocale && $hideDefaultLocale) {
                $redirection = localization()->getNonLocalizedURL();
            }
        }
        elseif ($currentLocale !== $defaultLocale || ! $hideDefaultLocale) {
            // If the current url does not contain any locale
            // The system redirect the user to the very same url "localized"
            // we use the current locale to redirect him
            $redirection = localization()->getLocalizedURL();
        }

        return $redirection;
    }
}
