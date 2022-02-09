<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Infrastructure;

use Firebase\JWT\JWT;
use Kishlin\Backend\Account\Infrastructure\RefreshTokenParserUsingFirebase;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Infrastructure\RefreshTokenParserUsingFirebase
 */
final class RefreshTokenParserUsingFirebaseTest extends TestCase
{
    private const SECRET_KEY = 'ThisKeyIsNotSoSecretButItIsTests';
    private const ALGORITHM  = 'HS256';

    public function testItParsesAPayloadFromToken(): void
    {
        $salt   = 'salt';
        $userId = 'uuid';
        $parser = new RefreshTokenParserUsingFirebase(self::SECRET_KEY, self::ALGORITHM);

        $refreshToken = JWT::encode(['user' => $userId, 'salt' => $salt], self::SECRET_KEY, self::ALGORITHM);

        $out = $parser->payloadFromRefreshToken($refreshToken);

        self::assertSame($userId, $out->userId());
        self::assertSame($salt, $out->salt());
    }
}
