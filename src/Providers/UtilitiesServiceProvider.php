<?php namespace Arcanedev\Localization\Providers;

use Arcanedev\Localization\Utilities\LocalesManager;
use Arcanedev\Localization\Utilities\RouteTranslator;
use Arcanedev\Support\ServiceProvider;

/**
 * Class     UtilitiesServiceProvider
 *
 * @package  Arcanedev\Localization\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UtilitiesServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRouteTranslator();
        $this->registerLocalesManager();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Utilities
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register RouteTranslator utility.
     */
    private function registerRouteTranslator()
    {
        $this->app->singleton('arcanedev.localization.translator', function ($app) {
            return new RouteTranslator($app['translator']);
        });
    }

    /**
     * Register LocalesManager utility.
     */
    private function registerLocalesManager()
    {
        $this->app->singleton('arcanedev.localization.locales-manager', function ($app) {
            return new LocalesManager($app['config']);
        });
    }
}
