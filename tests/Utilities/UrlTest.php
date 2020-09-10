<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Tests\Utilities;

use Arcanedev\Localization\Tests\TestCase;
use Arcanedev\Localization\Utilities\Url;

/**
 * Class     UrlTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UrlTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_unparse_url(): void
    {
        $urls = [
            '',
            'http://www.example.com/',
            'http://example.com:80/',
            'http://demo.example.com:80/',
            'http://username:password@example.com/',
            'http://name:pass@example.com/',
            'http://name:passd@admin.example.com/',
            'http://name:passd@admin.example.com:80/',
            'http://example.com/products',
            'http://shop.example.com/products',
            'http://example.com/products?sku=1234',
            'http://shop.example.com/products?sku=1234',
            'http://name:pass@shop.example.com:80/products?sku=1234#price',
        ];

        foreach ($urls as $url) {
            $parsed = ! empty($url) ? parse_url($url) : [];

            static::assertSame($url, Url::unparse($parsed));
        }
    }

    /** @test */
    public function it_can_substitute_route_bindings(): void
    {
        $attributes = [
            'user' => new \Arcanedev\Localization\Tests\Stubs\User('admin'),
        ];

        static::assertSame(
            'users/admin',
            Url::substituteAttributes($attributes, 'users/{user}')
        );
    }
}
