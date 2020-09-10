<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Tests\Middleware;

use Arcanedev\Localization\Tests\TestCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

/**
 * Class     LocaleSessionRedirectTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleSessionRedirectTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_redirect_with_locale_session(): void
    {
        $this->refreshApplication(false, true);
        session()->put('locale', 'fr');

        /** @var Response|RedirectResponse $response */
        $response = $this->call('GET', $this->testUrlOne);

        static::assertSame(302, $response->getStatusCode());
        static::assertSame($this->testUrlOne . 'fr', $response->getTargetUrl());

        session()->put('locale', 'es');

        $response = $this->call('GET', $this->testUrlOne);

        static::assertSame(302, $response->getStatusCode());
        static::assertSame($this->testUrlOne . 'es', $response->getTargetUrl());
    }

    /** @test */
    public function it_can_pass_redirect_without_session(): void
    {
        $this->refreshApplication(false, true);
        session()->put('locale', null);

        /** @var RedirectResponse $response */
        $response = $this->call('GET', $this->testUrlOne);

        static::assertSame(302, $response->getStatusCode());
        static::assertSame($this->testUrlOne . 'en', $response->getTargetUrl());
    }
}
