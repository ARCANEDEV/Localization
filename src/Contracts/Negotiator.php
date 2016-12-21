<?php namespace Arcanedev\Localization\Contracts;

use Illuminate\Http\Request;

/**
 * Interface  Negotiator
 *
 * @package   Arcanedev\Localization\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Negotiator
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Negotiate the request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return string
     */
    public function negotiate(Request $request);
}
