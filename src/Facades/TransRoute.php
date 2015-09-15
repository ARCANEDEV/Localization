<?php namespace Arcanedev\Localization\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class     TransRoute
 *
 * @package  Arcanedev\Localization\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransRoute extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor() {
        return 'arcanedev.localization.router';
    }
}
