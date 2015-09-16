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
     * Boot the package.
     */
    public function boot()
    {
        parent::boot();

        $this->publishes([
            $this->getConfigFile() => config_path("{$this->package}.php"),
        ], 'config');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerLocalization();
        $this->app->register(Providers\RouterServiceProvider::class);
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
            return new Localization($app);
        });

        $this->addFacade(
            $this->app['config']->get('localization.facade', 'Localization'),
            Facades\Localization::class
        );
    }
}
