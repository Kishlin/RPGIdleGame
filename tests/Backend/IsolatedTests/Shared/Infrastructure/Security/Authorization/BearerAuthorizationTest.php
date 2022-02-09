<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Infrastructure\Security\Authorization;

use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\BearerAuthorization;
use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\FailedToDecodeHeaderException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Security\Authorization\BearerAuthorization
 */
final class BearerAuthorizationTest extends TestCase
{
    public function testItCanRetrieveDataFromAHeader(): void
    {
        $data = BearerAuthorization::fromHeader('Bearer token');

        self::assertSame('token', $data->token());
    }

    public function testItThrowsAnExceptionIfHeaderIsInvalid(): void
    {
        self::expectException(FailedToDecodeHeaderException::class);
        BearerAuthorization::fromHeader('Invalid header');
    }
}
