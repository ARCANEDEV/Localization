<?php namespace Arcanedev\Localization\Contracts;

use Illuminate\Http\Request;

/**
 * Interface  NegotiatorInterface
 *
 * @package   Arcanedev\Localization\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface NegotiatorInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Negotiate the request.
     *
     * @param  Request  $request
     *
     * @return string
     */
    public function negotiate(Request $request);
}
