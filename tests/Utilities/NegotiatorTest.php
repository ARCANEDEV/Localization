<?php namespace Arcanedev\Localization\Tests\Utilities;

use Arcanedev\Localization\Tests\TestCase;
use Arcanedev\Localization\Utilities\Negotiator;
use Illuminate\Http\Request;
use Prophecy\Prophecy\ObjectProphecy;

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
    public function it_can_be_instantiated()
    {
        static::assertInstanceOf(Negotiator::class, $this->negotiator);
    }

    /** @test */
    public function it_can_negotiate_supported_accepted_languages_header()
    {
        $languages = [
            ['en', 'en-us, en; q=0.5'],
            ['fr', 'en; q=0.5, fr; q=1.0'],
            ['es', 'es; q=0.6, en; q=0.5, fr; q=0.5'],
        ];

        foreach ($languages as $language) {
            /** @var Request $request */
            $request = $this->mockRequestWithAcceptLanguage($language[1])->reveal();

            static::assertSame($language[0], $this->negotiator->negotiate($request));
        }
    }

    /** @test */
    public function it_can_negotiate_any_accepted_languages_header()
    {
        /** @var Request $request */
        $request = $this->mockRequestWithAcceptLanguage('*')->reveal();

        static::assertSame('en', $this->negotiator->negotiate($request));
    }

    /** @test */
    public function it_can_negotiate_supported_http_accepted_languages_server()
    {
        /** @var Request $request */
        $request = $this->mockRequestWithHttpAcceptLanguage('fr;q=0.8,en;q=0.4', 'jp; q=1.0')->reveal();

        static::assertSame('fr', $this->negotiator->negotiate($request));

        $request = $this->mockRequestWithHttpAcceptLanguage('fr;q=0.8,en;q=0.4', '*/*')->reveal();

        static::assertSame('fr', $this->negotiator->negotiate($request));
    }

    /** @test */
    public function it_can_negotiate_supported_remote_host_server()
    {
        /** @var Request $request */
        $request = $this->mockRequestWithRemoteHostServer(
            'http://www.omelette-au-fromage.fr',
            'ar;q=0.8,sv;q=0.4',
            'jp; q=1.0'
        )->reveal();

        static::assertSame('fr', $this->negotiator->negotiate($request));
    }

    /** @test */
    public function it_can_negotiate_unsupported_remote_host_server()
    {
        /** @var Request $request */
        $request = $this->mockRequestWithRemoteHostServer(
            'http://www.sushi.jp',
            'ar;q=0.8,sv;q=0.4',
            'jp; q=1.0'
        )->reveal();

        static::assertSame('en', $this->negotiator->negotiate($request));
    }

    /** @test */
    public function it_can_negotiate_undefined_remote_host_server()
    {
        /** @var Request $request */
        $request = $this->mockRequestWithRemoteHostServer(
            null,
            'ar;q=0.8,sv;q=0.4',
            'jp; q=1.0'
        )->reveal();

        static::assertSame('en', $this->negotiator->negotiate($request));
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Mock request.
     *
     * @return ObjectProphecy
     */
    private function mockRequest()
    {
        $request = $this->prophesize(Request::class);

        return $request;
    }

    /**
     * Mock request with accept language header.
     *
     * @param  string  $acceptLanguages
     *
     * @return ObjectProphecy
     */
    private function mockRequestWithAcceptLanguage($acceptLanguages)
    {
        $request = $this->mockRequest();

        $request->header('Accept-Language')
            ->willReturn($acceptLanguages)
            ->shouldBeCalled();

        return $request;
    }

    /**
     * Mock request with HTTP Accept Language server.
     *
     * @param  string  $acceptLanguages
     *
     * @return ObjectProphecy
     */
    private function mockRequestWithHttpAcceptLanguage($httpAcceptLanguages, $acceptLanguages)
    {
        $request = $this->mockRequestWithAcceptLanguage($acceptLanguages);

        $request->server('HTTP_ACCEPT_LANGUAGE')
                ->willReturn($httpAcceptLanguages)
                ->shouldBeCalled();

        return $request;
    }

    /**
     * Mock request with REMOTE_HOST server.
     *
     * @param  string  $remoteHost
     * @param  string  $httpAcceptLanguages
     * @param  string  $acceptLanguages
     *
     * @return ObjectProphecy
     */
    private function mockRequestWithRemoteHostServer($remoteHost, $httpAcceptLanguages, $acceptLanguages)
    {
        $request = $this->mockRequestWithHttpAcceptLanguage($httpAcceptLanguages, $acceptLanguages);

        $request->server('REMOTE_HOST')
            ->willReturn($remoteHost)
            ->shouldBeCalled();

        return $request;
    }
}
