<?php namespace Arcanedev\Localization\Tests\Stubs\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as BaseEncrypter;

/**
 * Class     EncryptCookies
 *
 * @package  Arcanedev\Localization\Tests\Stubs\Http\Middleware
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class EncryptCookies extends BaseEncrypter
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        'locale'
    ];
}
