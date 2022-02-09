<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Infrastructure\Security;

use Firebase\JWT\JWT;
use Kishlin\Backend\Shared\Infrastructure\Security\TokenParserUsingFirebase;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Security\TokenParserUsingFirebase
 */
final class TokenParserUsingFirebaseTest extends TestCase
{
    private const SECRET_KEY = 'ThisKeyIsNotSoSecretButItIsTests';
    private const ALGORITHM  = 'HS256';

    public function testItParsesAPayloadFromToken(): void
    {
        $userId = 'uuid';
        $parser = new TokenParserUsingFirebase(self::SECRET_KEY, self::ALGORITHM);

        $token = JWT::encode(['user' => $userId], self::SECRET_KEY, self::ALGORITHM);

        $out = $parser->payloadFromToken($token);

        self::assertSame($userId, $out->userId());
    }
}
