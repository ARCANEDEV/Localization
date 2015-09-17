<?php namespace Arcanedev\Localization\Traits;

/**
 * Class     LocalizationKernelTrait
 *
 * @package  Arcanedev\Localization\Traits
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait LocalizationKernelTrait
{
    /**
     * Get the route dispatcher callback.
     *
     * @return \Closure
     */
    protected function dispatchToRouter()
    {
        $this->router = $this->app['router'];

        return parent::dispatchToRouter();
    }
}
