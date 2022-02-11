<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\Security;

use Kishlin\Backend\Shared\Domain\Security\RefreshTokenPayload;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\Security\RefreshTokenPayload
 */
final class RefreshTokenPayloadTest extends TestCase
{
    public function testItCanBeCreated(): void
    {
        $user = 'user';
        $salt = 'salt';

        $token = RefreshTokenPayload::fromScalars($user, $salt);

        self::assertSame($user, $token->userId());
        self::assertSame($salt, $token->salt());
    }
}
