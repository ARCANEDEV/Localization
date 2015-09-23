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

            $this->router->get('test', [
                'as'    => 'test',
                function () {
                    return app('translator')->get('localization::routes.test-text');
                }
            ]);

            $this->router->transGet('localization::routes.about', [
                'as'    => 'about',
                function () {
                    return localization()->getLocalizedURL('es') ?: 'Not url available';
                }
            ]);

            $this->router->transGet('localization::routes.view', [
                'as'    => 'view',
                function () {
                    return localization()->getLocalizedURL('es') ?: 'Not url available';
                }
            ]);

            $this->router->transGet('localization::routes.view-project', [
                'as'    => 'view-project',
                function () {
                    return localization()->getLocalizedURL('es') ?: 'Not url available';
                }
            ]);

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

            $this->router->transPut('localization::routes.methods.put', [
                'as'    => 'method.put',
                function () {
                    return 'PUT method';
                }
            ]);

            $this->router->transPatch('localization::routes.methods.patch', [
                'as'    => 'method.patch',
                function () {
                    return 'PATCH method';
                }
            ]);

            $this->router->transOptions('localization::routes.methods.options', [
                'as'    => 'method.options',
                function () {
                    return 'OPTIONS method';
                }
            ]);

            $this->router->transDelete('localization::routes.methods.delete', [
                'as'    => 'method.delete',
                function () {
                    return 'DELETE method';
                }
            ]);

            $this->router->transAny('localization::routes.methods.any', [
                'as'    => 'method.any',
                function () {
                    return 'Any method';
                }
            ]);

            /* ------------------------------------------------------------------------------------------------
             |  Resource Controller
             | ------------------------------------------------------------------------------------------------
             */
            $this->router->resource('dummy', Controllers\DummyController::class);

            $this->router->group(['prefix'  => 'foo'], function () {
                $this->router->resource('Bar', Controllers\BarController::class);
            });
        });
    }
}
