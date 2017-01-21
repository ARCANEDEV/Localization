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
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     */
    public function register()
    {
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
            'arcanedev.localization.translator',
            LocalesManagerContract::class,
            'arcanedev.localization.locales-manager',
            NegotiatorContract::class,
            'arcanedev.localization.negotiator',
        ];
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
        $this->singleton(RouteTranslatorContract::class, function ($app) {
            return new RouteTranslator($app['translator']);
        });
        $this->singleton('arcanedev.localization.translator', RouteTranslatorContract::class);
    }

    /**
     * Register LocalesManager utility.
     */
    private function registerLocalesManager()
    {
        $this->singleton(LocalesManagerContract::class, function ($app) {
            return new LocalesManager($app);
        });
        $this->singleton('arcanedev.localization.locales-manager', LocalesManagerContract::class);
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
        $this->bind('arcanedev.localization.negotiator', NegotiatorContract::class);
    }
}
