<?php namespace Arcanedev\Localization\Tests\Stubs\Http;

use Arcanedev\Localization\Traits\LocalizationKernelTrait;
use Orchestra\Testbench\Http\Kernel as HttpKernel;

/**
 * Class     Kernel
 *
 * @package  Arcanedev\Localization\Tests\Stubs\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Kernel extends HttpKernel
{
    /* ------------------------------------------------------------------------------------------------
     |  Traits
     | ------------------------------------------------------------------------------------------------
     */
    use LocalizationKernelTrait;

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    ];
}
