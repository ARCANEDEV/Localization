<?php namespace Arcanedev\Localization;

use Arcanedev\Support\PackageServiceProvider;

/**
 * Class     LocalizationServiceProvider
 *
 * @package  Arcanedev\Localization
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocalizationServiceProvider extends PackageServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'localization';

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the base path of the package.
     *
     * @return string
     */
    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerProviders([
            Providers\RoutingServiceProvider::class,
            Providers\UtilitiesServiceProvider::class,
        ]);
        $this->registerLocalization();
        $this->registerAliases();
    }

    /**
     * Boot the package.
     */
    public function boot()
    {
        parent::boot();

        $this->publishConfig();
        $this->publishViews();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Contracts\Localization::class,
            'arcanedev.localization',
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Services Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register Localization.
     */
    private function registerLocalization()
    {
        $this->singleton(Contracts\Localization::class, function($app) {
            return new Localization(
                $app,
                $app[Contracts\RouteTranslator::class],
                $app[Contracts\LocalesManager::class]
            );
        });

        $this->singleton('arcanedev.localization', Contracts\Localization::class);

        $this->alias(
            $this->config()->get('localization.facade', 'Localization'),
            Facades\Localization::class
        );
    }
}
