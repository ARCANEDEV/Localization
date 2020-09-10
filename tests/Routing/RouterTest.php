<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Tests\Routing;

use Illuminate\Routing\Router;
use Arcanedev\Localization\Tests\TestCase;

/**
 * Class     RouterTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouterTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Localization\Routing\Router */
    private $router;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->router = app('router');
    }

    public function tearDown(): void
    {
        unset($this->router);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        static::assertInstanceOf(\Illuminate\Routing\Router::class, $this->router);
        static::assertInstanceOf(Router::class, $this->router);
    }

    /** @test */
    public function it_can_get_and_check_all_routes(): void
    {
        $routes     = $this->router->getRoutes();
        $routeNames = $this->routeRegistrar->getRouteNames();

        static::assertInstanceOf(\Illuminate\Routing\RouteCollection::class, $routes);
        static::assertNotEmpty($routes->count());

        foreach ($routeNames as $name) {
            static::assertTrue($this->router->has($name), "The route name [$name] not found.");
        }
    }
}
