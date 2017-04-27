<?php namespace Arcanedev\Localization\Providers;

use Arcanedev\Localization\Contracts\LocalesManager as LocalesManagerContract;
use Arcanedev\Localization\Contracts\Negotiator as NegotiatorContract;
use Arcanedev\Localization\Contracts\RouteTranslator as RouteTranslatorContract;
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
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();

        $this->registerRouteTranslator();
        $this->registerLocalesManager();
        $this->registerLocaleNegotiator();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            RouteTranslatorContract::class,
            LocalesManagerContract::class,
            NegotiatorContract::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Utilities
     | -----------------------------------------------------------------
     */

    /**
     * Register RouteTranslator utility.
     */
    private function registerRouteTranslator()
    {
        $this->singleton(RouteTranslatorContract::class, function ($app) {
            return new RouteTranslator($app['translator']);
        });
    }

    /**
     * Register LocalesManager utility.
     */
    private function registerLocalesManager()
    {
        $this->singleton(LocalesManagerContract::class, LocalesManager::class);
    }

    /**
     * Register LocaleNegotiator utility.
     */
    private function registerLocaleNegotiator()
    {
        $this->bind(NegotiatorContract::class, function ($app) {
            /** @var  \Arcanedev\Localization\Contracts\LocalesManager  $manager */
            $manager = $app[LocalesManagerContract::class];

            return new Negotiator(
                $manager->getDefaultLocale(),
                $manager->getSupportedLocales()
            );
        });
    }
}
