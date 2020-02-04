<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Tests\Utilities;

use Arcanedev\Localization\Tests\TestCase;
use Arcanedev\Localization\Utilities\Negotiator;
use Illuminate\Http\Request;

/**
 * Class     NegotiatorTest
 *
 * @package  Arcanedev\Localization\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NegotiatorTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Localization\Contracts\Negotiator */
    private $negotiator;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->negotiator = app(\Arcanedev\Localization\Contracts\Negotiator::class);
    }

    public function tearDown(): void
    {
        unset($this->negotiator);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        static::assertInstanceOf(Negotiator::class, $this->negotiator);
    }

    /**
     * @test
     *
     * @dataProvider  provideAcceptedLanguages
     *
     * @param  string  $locale
     * @param  string  $acceptLanguages
     */
    public function it_can_negotiate_supported_accepted_languages_header(string $locale, string $acceptLanguages): void
    {
        $request = $this->mockRequestWithAcceptLanguage($acceptLanguages);

        static::assertSame($locale, $this->negotiator->negotiate($request));
    }

    public function provideAcceptedLanguages(): array
    {
        return [
            ['en', 'en-us, en; q=0.5'],
            ['fr', 'en; q=0.5, fr; q=1.0'],
            ['es', 'es; q=0.6, en; q=0.5, fr; q=0.5'],
        ];
    }

    /** @test */
    public function it_can_negotiate_any_accepted_languages_header(): void
    {
        /** @var Request $request */
        $request = $this->mockRequestWithAcceptLanguage('*');

        static::assertSame('en', $this->negotiator->negotiate($request));
    }

    /** @test */
    public function it_can_negotiate_supported_http_accepted_languages_server(): void
    {
        /** @var Request $request */
        $request = $this->mockRequestWithHttpAcceptLanguage('fr;q=0.8,en;q=0.4', 'jp; q=1.0');

        static::assertSame('fr', $this->negotiator->negotiate($request));

        $request = $this->mockRequestWithHttpAcceptLanguage('fr;q=0.8,en;q=0.4', '*/*');

        static::assertSame('fr', $this->negotiator->negotiate($request));
    }

    /** @test */
    public function it_can_negotiate_supported_remote_host_server(): void
    {
        /** @var Request $request */
        $request = $this->mockRequestWithRemoteHostServer(
            'http://www.omelette-au-fromage.fr',
            'ar;q=0.8,sv;q=0.4',
            'jp; q=1.0'
        );

        static::assertSame('fr', $this->negotiator->negotiate($request));
    }

    /** @test */
    public function it_can_negotiate_unsupported_remote_host_server(): void
    {
        /** @var Request $request */
        $request = $this->mockRequestWithRemoteHostServer(
            'http://www.sushi.jp',
            'ar;q=0.8,sv;q=0.4',
            'jp; q=1.0'
        );

        static::assertSame('en', $this->negotiator->negotiate($request));
    }

    /** @test */
    public function it_can_negotiate_undefined_remote_host_server(): void
    {
        /** @var Request $request */
        $request = $this->mockRequestWithRemoteHostServer(
            null,
            'ar;q=0.8,sv;q=0.4',
            'jp; q=1.0'
        );

        static::assertSame('en', $this->negotiator->negotiate($request));
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Mock request.
     *
     * @return object
     */
    private function mockRequest()
    {
        return $this->mock(Request::class);
    }

    /**
     * Mock request with accept language header.
     *
     * @param  string  $acceptLanguages
     *
     * @return object
     */
    private function mockRequestWithAcceptLanguage($acceptLanguages)
    {
        return tap(
            $this->mockRequest(),
            function ($request) use ($acceptLanguages) {
                $request->shouldReceive('header')
                    ->withArgs(['Accept-Language'])
                    ->andReturn($acceptLanguages);
            }
        );
    }

    /**
     * Mock request with HTTP Accept Language server.
     *
     * @param  string  $acceptLanguages
     * @param  string  $httpAcceptLanguages
     *
     * @return object
     */
    private function mockRequestWithHttpAcceptLanguage($httpAcceptLanguages, $acceptLanguages)
    {
        return tap(
            $this->mockRequestWithAcceptLanguage($acceptLanguages),
            function ($request) use ($httpAcceptLanguages) {
                $request->shouldReceive('server')
                        ->withArgs(['HTTP_ACCEPT_LANGUAGE'])
                        ->andReturn($httpAcceptLanguages);
            }
        );
    }

    /**
     * Mock request with REMOTE_HOST server.
     *
     * @param  string  $remoteHost
     * @param  string  $httpAcceptLanguages
     * @param  string  $acceptLanguages
     *
     * @return object
     */
    private function mockRequestWithRemoteHostServer($remoteHost, $httpAcceptLanguages, $acceptLanguages)
    {
        return tap(
            $this->mockRequestWithHttpAcceptLanguage($httpAcceptLanguages, $acceptLanguages),
            function ($request) use ($remoteHost) {
                $request->shouldReceive('server')
                        ->withArgs(['REMOTE_HOST'])
                        ->andReturn($remoteHost);
            }
        );
    }
}
