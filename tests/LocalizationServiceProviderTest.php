<?php namespace Arcanedev\Localization\Tests;

use Arcanedev\Localization\LocalizationServiceProvider;

/**
 * Class     LocalizationServiceProviderTest
 *
 * @package  Arcanedev\Localization\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocalizationServiceProviderTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var LocalizationServiceProvider */
    private $provider;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(LocalizationServiceProvider::class);
    }

    public function tearDown()
    {
        unset($this->provider);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(LocalizationServiceProvider::class, $this->provider);
    }

    /** @test */
    public function it_can_provides()
    {
        $provided = $this->provider->provides();

        $this->assertCount(1, $provided);
        $this->assertEquals([
            'arcanedev.localization',
        ], $provided);
    }

    /** @test */
    public function it_can_register_localization_facade()
    {
        $this->assertEquals(
            $this->app->getLocale(),
            \Localization::getDefaultLocale()
        );
    }
}
