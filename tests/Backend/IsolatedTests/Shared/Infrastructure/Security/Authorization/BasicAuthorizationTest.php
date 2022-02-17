<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Infrastructure\Security\Authorization;

use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\BasicAuthorization;
use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\FailedToReadCookieException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Security\Authorization\BasicAuthorization
 */
final class BasicAuthorizationTest extends TestCase
{
    public function testItCanRetrieveDataFromAHeader(): void
    {
        $data = BasicAuthorization::fromHeader('Basic dXNlcm5hbWU6cGFzc3dvcmQ='); // base64_encode('username:password')

        self::assertSame('username', $data->username());
        self::assertSame('password', $data->password());
    }

    public function testItThrowsAnExceptionIfHeaderIsInvalid(): void
    {
        self::expectException(FailedToReadCookieException::class);
        BasicAuthorization::fromHeader('Invalid header');
    }

    public function testItThrowsAnExceptionIfDataCannotBeDecoded(): void
    {
        self::expectException(FailedToReadCookieException::class);
        BasicAuthorization::fromHeader('Basic not-a-base64-string');
    }

    public function testItThrowsAnExceptionIfDataIsIncomplete(): void
    {
        self::expectException(FailedToReadCookieException::class);
        BasicAuthorization::fromHeader('Basic aW5jb21wbGV0ZQ=='); // base64_encode('incomplete')
    }

    public function testItThrowsAnExceptionIfThereIsTooMuchData(): void
    {
        self::expectException(FailedToReadCookieException::class);
        BasicAuthorization::fromHeader('Basic dG9vOm11Y2g6ZGF0YQ=='); // base64_encode('too:much:data')
    }
}
