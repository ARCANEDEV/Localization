<?php namespace Arcanedev\Localization\Tests\Routing;

use Arcanedev\Localization\Routing\Router;
use Arcanedev\Localization\Tests\TestCase;

/**
 * Class     RouterTest
 *
 * @package  Arcanedev\Localization\Tests\Routing
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouterTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Router */
    private $router;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->router = app('router');
    }

    public function tearDown()
    {
        unset($this->router);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(\Illuminate\Routing\Router::class, $this->router);
        $this->assertInstanceOf(Router::class, $this->router);
    }

    /** @test */
    public function it_can_get_and_check_all_routes()
    {
        $routes     = $this->router->getRoutes();
        $routeNames = $this->routeRegistrar->getRouteNames();

        $this->assertInstanceOf(\Illuminate\Routing\RouteCollection::class, $routes);
        $this->assertNotEmpty($routes->count());

        foreach ($routeNames as $name) {
            $this->assertTrue($this->router->has($name), "The route name [$name] not found.");
        }
    }
}
