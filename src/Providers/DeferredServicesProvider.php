<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Providers;

use Arcanedev\Localization\Contracts\LocalesManager as LocalesManagerContract;
use Arcanedev\Localization\Contracts\Localization as LocalizationContract;
use Arcanedev\Localization\Contracts\Negotiator as NegotiatorContract;
use Arcanedev\Localization\Contracts\RouteTranslator as RouteTranslatorContract;
use Arcanedev\Localization\Localization;
use Arcanedev\Localization\Utilities\{LocalesManager, Negotiator, RouteTranslator};
use Arcanedev\Support\Providers\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class     DeferredServicesProvider
 *
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
    public function provides(): array
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
    private function registerLocalization(): void
    {
        $this->singleton(LocalizationContract::class, Localization::class);
    }

    /**
     * Register RouteTranslator utility.
     */
    private function registerRouteTranslator(): void
    {
        $this->singleton(RouteTranslatorContract::class, RouteTranslator::class);
    }

    /**
     * Register LocalesManager utility.
     */
    private function registerLocalesManager(): void
    {
        $this->singleton(LocalesManagerContract::class, LocalesManager::class);
    }

    /**
     * Register LocaleNegotiator utility.
     */
    private function registerLocaleNegotiator(): void
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
