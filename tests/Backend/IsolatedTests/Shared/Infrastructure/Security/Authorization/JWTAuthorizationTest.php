<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Infrastructure\Security\Authorization;

use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\FailedToReadCookieException;
use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\JWTAuthorization;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Security\Authorization\JWTAuthorization
 */
final class JWTAuthorizationTest extends TestCase
{
    public function testItCanRetrieveDataFromACookie(): void
    {
        $data = JWTAuthorization::fromCookie('token');

        self::assertSame('token', $data->token());
    }

    public function testItThrowsAnExceptionIfCookieIsInvalid(): void
    {
        self::expectException(FailedToReadCookieException::class);
        JWTAuthorization::fromCookie(null);
    }
}
