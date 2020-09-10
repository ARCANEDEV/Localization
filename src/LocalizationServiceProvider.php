<?php

declare(strict_types=1);

namespace Arcanedev\Localization;

use Arcanedev\Support\Providers\PackageServiceProvider;

/**
 * Class     LocalizationServiceProvider
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocalizationServiceProvider extends PackageServiceProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'localization';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->registerConfig();

        $this->registerProvider(Providers\RoutingServiceProvider::class);
    }

    /**
     * Boot the package.
     */
    public function boot(): void
    {
        $this->loadViews();

        if ($this->app->runningInConsole()) {
            $this->publishConfig();
            $this->publishViews();
        }
    }
}
