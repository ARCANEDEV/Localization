<?php namespace Arcanedev\Localization\Routing;

use Illuminate\Routing\ResourceRegistrar as IlluminateResourceRegistrar;

/**
 * Class     ResourceRegistrar
 *
 * @package  Arcanedev\Localization\Routing
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ResourceRegistrar extends IlluminateResourceRegistrar
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the resource name for a grouped resource.
     *
     * @param  string  $prefix
     * @param  string  $resource
     * @param  string  $method
     * @return string
     */
    protected function getGroupResourceName($prefix, $resource, $method)
    {
        $currentLocale = localization()->getCurrentLocale();
        $group         = trim(str_replace('/', '.', $this->router->getLastGroupPrefix()), '.');
        $group         = str_replace($currentLocale . '.', '', $group);

        if ( ! empty($group) && $group !== $currentLocale) {
            return trim("{$prefix}{$group}.{$resource}.{$method}", '.');
        }

        return trim("{$prefix}{$resource}.{$method}", '.');
    }
}
