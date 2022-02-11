<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\Security;

use Kishlin\Backend\Shared\Domain\Security\TokenPayload;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\Security\TokenPayload
 */
final class TokenPayloadTest extends TestCase
{
    public function testItCanBeCreated(): void
    {
        $user = 'user';

        self::assertSame($user, TokenPayload::fromScalars($user)->userId());
    }
}
