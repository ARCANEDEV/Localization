<?php namespace Arcanedev\Localization\Providers;

use Arcanedev\Localization\Utilities\LocalesManager;
use Arcanedev\Localization\Utilities\Negotiator;
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
        $this->registerLocaleNegotiator();
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
            return new LocalesManager($app);
        });
    }

    /**
     * Register LocaleNegotiator utility.
     */
    private function registerLocaleNegotiator()
    {
        $this->app->bind('arcanedev.localization.negotiator', function ($app) {
            /** @var LocalesManager $localesManager */
            $localesManager = $app['arcanedev.localization.locales-manager'];

            return new Negotiator(
                $localesManager->getDefaultLocale(),
                $localesManager->getSupportedLocales()
            );
        });
    }
}
