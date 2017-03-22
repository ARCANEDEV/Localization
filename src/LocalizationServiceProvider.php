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
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();

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
        $this->singleton(Contracts\Localization::class, Localization::class);
        $this->singleton('arcanedev.localization', Contracts\Localization::class);

        $this->alias(
            $this->config()->get('localization.facade', 'Localization'),
            Facades\Localization::class
        );
    }
}
