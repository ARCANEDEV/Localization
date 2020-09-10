<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Contracts;

use Arcanedev\Localization\Entities\LocaleCollection;
use Illuminate\Http\Request;

/**
 * Interface  Negotiator
 *
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Negotiator
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make Negotiator instance.
     *
     * @param  string                                             $defaultLocale
     * @param  \Arcanedev\Localization\Entities\LocaleCollection  $supportedLanguages
     *
     * @return self
     */
    public static function make($defaultLocale, LocaleCollection $supportedLanguages);

    /**
     * Negotiate the request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return string
     */
    public function negotiate(Request $request);
}
