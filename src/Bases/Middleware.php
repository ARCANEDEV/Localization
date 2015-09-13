<?php namespace Arcanedev\Localization\Bases;

use Arcanedev\Localization\Localization;
use Arcanedev\Support\Bases\Middleware as BaseMiddleware;
use Illuminate\Http\RedirectResponse;

/**
 * Class     Middleware
 *
 * @package  Arcanedev\Localization\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Middleware extends BaseMiddleware
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $locale;

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check is default locale hidden.
     *
     * @param  false|string  $locale
     *
     * @return bool
     */
    protected function isDefaultLocaleHidden($locale)
    {
        return localization()->getDefaultLocale() === $locale && localization()->hideDefaultLocaleInURL();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the redirection response.
     *
     * @param  false|string  $locale
     *
     * @return RedirectResponse
     */
    protected function getRedirection($locale)
    {
        $redirection = localization()->getLocalizedURL($locale);

        if ( ! is_string($redirection)) {
            return null;
        }

        return new RedirectResponse($redirection, 302, [
            'Vary' => 'Accept-Language'
        ]);
    }
}
