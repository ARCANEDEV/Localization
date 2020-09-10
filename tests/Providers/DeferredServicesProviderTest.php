<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Tests\Providers;

use Arcanedev\Localization\Providers\DeferredServicesProvider;
use Arcanedev\Localization\Tests\TestCase;

/**
 * Class     DeferredServicesProviderTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DeferredServicesProviderTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Localization\Providers\DeferredServicesProvider */
    private $provider;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(DeferredServicesProvider::class);
    }

    public function tearDown(): void
    {
        unset($this->provider);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $expectations = [
            \Illuminate\Support\ServiceProvider::class,
            \Illuminate\Contracts\Support\DeferrableProvider::class,
            \Arcanedev\Support\Providers\ServiceProvider::class,
            \Arcanedev\Localization\Providers\DeferredServicesProvider::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->provider);
        }
    }

    /** @test */
    public function it_can_provides(): void
    {
        $expected = [
            \Arcanedev\Localization\Contracts\Localization::class,
            \Arcanedev\Localization\Contracts\RouteTranslator::class,
            \Arcanedev\Localization\Contracts\LocalesManager::class,
            \Arcanedev\Localization\Contracts\Negotiator::class,
        ];

        static::assertEquals($expected, $this->provider->provides());
    }
}
