<?php namespace Arcanedev\Localization\Tests\Middleware;

use Arcanedev\Localization\Tests\TestCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

/**
 * Class     LocaleSessionRedirectTest
 *
 * @package  Arcanedev\Localization\Tests\Middleware
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleCookieRedirectTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_redirect_with_locale_cookie()
    {
        $this->refreshApplication(false, false, true);

        /** @var Response|RedirectResponse $response */
        $response = $this->call('GET', $this->testUrlOne, [], ['locale' => 'fr']);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame($this->testUrlOne . 'fr', $response->getTargetUrl());

        $response = $this->call('GET', $this->testUrlOne, [], ['locale' => 'es']);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame($this->testUrlOne . 'es', $response->getTargetUrl());
    }

    /** @test */
    public function it_can_pass_redirect_without_cookie()
    {
        $this->refreshApplication(false, true);
        session()->put('locale', null);

        /** @var RedirectResponse $response */
        $response = $this->call('GET', $this->testUrlOne);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame($this->testUrlOne . 'en', $response->getTargetUrl());
    }
}
