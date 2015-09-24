<?php namespace Arcanedev\Localization\Tests\Stubs\Http;

use Arcanedev\Localization\Routing\Router;

/**
 * Class     RouteRegistrar
 *
 * @package  Arcanedev\Localization\Tests\Stubs\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouteRegistrar
{
    /**
     * @var Router
     */
    protected $router;

    /** @var array */
    protected $routeNames = [];

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the router.
     *
     * @param  Router  $router
     *
     * @return self
     */
    private function setRouter(Router $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * Get route names collection.
     *
     * @return array
     */
    public function getRouteNames()
    {
        return $this->routeNames;
    }

    /**
     * Set route names to routes collection.
     *
     * @param  array  $names
     *
     * @return self
     */
    private function setRouteNames(array $names)
    {
        foreach ($names as $name) {
            $this->setRouteName($name);
        }

        return $this;
    }

    /**
     * Set route name to routes collection.
     *
     * @param  string  $name
     *
     * @return self
     */
    private function setRouteName($name)
    {
        if ( ! empty($name)) {
            $this->routeNames[] = $name;
        }

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Map the routes.
     *
     * @param  Router  $router
     */
    public function map(Router $router)
    {
        $this->setRouter($router);

        $this->router->localizedGroup(function () {
            $this->router->get('/', [
                'as'    =>  'index',
                function () {
                    return app('translator')->get('localization::routes.hello');
                }
            ]);
            $this->setRouteName('index');

            $this->router->get('test', [
                'as'    => 'test',
                function () {
                    return app('translator')->get('localization::routes.test-text');
                }
            ]);
            $this->setRouteName('test');

            $this->router->transGet('localization::routes.about', [
                'as'    => 'about',
                function () {
                    return localization()->getLocalizedURL('es') ?: 'Not url available';
                }
            ]);
            $this->setRouteName('about');

            $this->router->transGet('localization::routes.view', [
                'as'    => 'view',
                function () {
                    return localization()->getLocalizedURL('es') ?: 'Not url available';
                }
            ]);
            $this->setRouteName('view');

            $this->router->transGet('localization::routes.view-project', [
                'as'    => 'view-project',
                function () {
                    return localization()->getLocalizedURL('es') ?: 'Not url available';
                }
            ]);
            $this->setRouteName('view-project');

            /* ------------------------------------------------------------------------------------------------
             |  Other method
             | ------------------------------------------------------------------------------------------------
             */
            $this->router->transPost('localization::routes.methods.post', [
                'as'    => 'method.post',
                function () {
                    return 'POST method';
                }
            ]);
            $this->setRouteName('method.post');

            $this->router->transPut('localization::routes.methods.put', [
                'as'    => 'method.put',
                function () {
                    return 'PUT method';
                }
            ]);
            $this->setRouteName('method.put');

            $this->router->transPatch('localization::routes.methods.patch', [
                'as'    => 'method.patch',
                function () {
                    return 'PATCH method';
                }
            ]);
            $this->setRouteName('method.patch');

            $this->router->transOptions('localization::routes.methods.options', [
                'as'    => 'method.options',
                function () {
                    return 'OPTIONS method';
                }
            ]);
            $this->setRouteName('method.options');

            $this->router->transDelete('localization::routes.methods.delete', [
                'as'    => 'method.delete',
                function () {
                    return 'DELETE method';
                }
            ]);
            $this->setRouteName('method.delete');

            $this->router->transAny('localization::routes.methods.any', [
                'as'    => 'method.any',
                function () {
                    return 'Any method';
                }
            ]);
            $this->setRouteName('method.any');

            /* ------------------------------------------------------------------------------------------------
             |  Resource Controller
             | ------------------------------------------------------------------------------------------------
             */
            $this->router->resource('dummy', Controllers\DummyController::class);
            $this->setRouteNames([
                'dummy.index',
                'dummy.create',
                'dummy.store',
                'dummy.show',
                'dummy.edit',
                'dummy.update', // PUT
                'dummy.update', // PATCH
                'dummy.destroy',
            ]);

            $this->router->group(['prefix'  => 'foo'], function () {
                $this->router->resource('bar', Controllers\BarController::class);
            });
            $this->setRouteNames([
                'foo.bar.index',
                'foo.bar.create',
                'foo.bar.store',
                'foo.bar.show',
                'foo.bar.edit',
                'foo.bar.update', // PUT
                'foo.bar.update', // PATCH
                'foo.bar.destroy',
            ]);
        });
    }
}
