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
     * Vendor name.
     *
     * @var string
     */
    protected $vendor   = 'arcanedev';

    /**
     * Package name.
     *
     * @var string
     */
    protected $package  = 'localization';

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

        $this->app->register(Providers\RoutingServiceProvider::class);
        $this->app->register(Providers\UtilitiesServiceProvider::class);
        $this->registerLocalization();
    }

    /**
     * Boot the package.
     */
    public function boot()
    {
        parent::boot();

        $this->publishConfig();
        $this->registerViews();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['arcanedev.localization'];
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
        $this->app->singleton('arcanedev.localization', function($app) {
            $routeTranslator = $app['arcanedev.localization.translator'];

            return new Localization($app, $routeTranslator);
        });

        $this->addFacade(
            $this->app['config']->get('localization.facade', 'Localization'),
            Facades\Localization::class
        );
    }

    /* ------------------------------------------------------------------------------------------------
     |  Resources
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Publishes configs.
     */
    private function publishConfig()
    {
        $this->publishes([
            $this->getConfigFile() => config_path("{$this->package}.php"),
        ], 'config');
    }

    /**
     * Register and published Views.
     */
    private function registerViews()
    {
        $viewsPath = $this->getBasePath() . '/resources/views';

        $this->loadViewsFrom($viewsPath, $this->package);
        $this->publishes([
            $viewsPath => base_path('resources/views/vendor/' . $this->package),
        ], 'views');
    }
}
