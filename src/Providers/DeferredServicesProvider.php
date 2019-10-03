<?php namespace Arcanedev\Localization\Providers;

use Arcanedev\Localization\Contracts\{
    LocalesManager as LocalesManagerContract,
    Localization as LocalizationContract,
    Negotiator as NegotiatorContract,
    RouteTranslator as RouteTranslatorContract
};
use Arcanedev\Localization\Localization;
use Arcanedev\Localization\Utilities\{LocalesManager, Negotiator, RouteTranslator};
use Arcanedev\Support\Providers\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class     DeferredServicesProvider
 *
 * @package  Arcanedev\Localization\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DeferredServicesProvider extends ServiceProvider implements DeferrableProvider
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerLocalization();
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
            LocalizationContract::class,
            RouteTranslatorContract::class,
            LocalesManagerContract::class,
            NegotiatorContract::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Services
     | -----------------------------------------------------------------
     */

    /**
     * Register Localization.
     */
    private function registerLocalization()
    {
        $this->singleton(LocalizationContract::class, Localization::class);
    }

    /**
     * Register RouteTranslator utility.
     */
    private function registerRouteTranslator()
    {
        $this->singleton(RouteTranslatorContract::class, RouteTranslator::class);
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
