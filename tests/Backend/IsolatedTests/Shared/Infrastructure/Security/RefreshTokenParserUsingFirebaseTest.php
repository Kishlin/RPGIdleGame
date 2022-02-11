<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Infrastructure\Security;

use Firebase\JWT\JWT;
use Kishlin\Backend\Shared\Domain\Security\ParsingTokenFailedException;
use Kishlin\Backend\Shared\Domain\Security\RefreshTokenPayload;
use Kishlin\Backend\Shared\Infrastructure\Security\RefreshTokenParserUsingFirebase;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Security\RefreshTokenParserUsingFirebase
 */
final class RefreshTokenParserUsingFirebaseTest extends TestCase
{
    private const SECRET_KEY = 'ThisKeyIsNotSoSecretButItIsTests';
    private const ALGORITHM  = 'HS256';

    public function testItParsesAPayloadFromToken(): void
    {
        $salt = 'salt';
        $user = 'uuid';

        $payload      = ['user' => $user, 'salt' => $salt, 'exp' => strtotime('+1 month')];
        $refreshToken = JWT::encode($payload, self::SECRET_KEY, self::ALGORITHM);

        $parser = new RefreshTokenParserUsingFirebase(self::SECRET_KEY, self::ALGORITHM, true);
        $out    = $parser->payloadFromRefreshToken($refreshToken);

        self::assertSame($user, $out->userId());
        self::assertSame($salt, $out->salt());
    }

    public function testItParsesATokenWithoutExpirationWhenItIsNotRequired(): void
    {
        $payload = ['user' => 'user', 'salt' => 'salt', 'exp' => strtotime('+1 minute')];

        $refreshToken = JWT::encode($payload, self::SECRET_KEY, self::ALGORITHM);

        $parser = new RefreshTokenParserUsingFirebase(self::SECRET_KEY, self::ALGORITHM, expirationClaimIsRequired: false);

        self::assertInstanceOf(RefreshTokenPayload::class, $parser->payloadFromRefreshToken($refreshToken));
    }

    public function testItFailsToParseATokenWithoutExpirationWhenItIsRequired(): void
    {
        $refreshToken = JWT::encode(['user' => 'user', 'salt' => 'salt'], self::SECRET_KEY, self::ALGORITHM);

        $parser = new RefreshTokenParserUsingFirebase(self::SECRET_KEY, self::ALGORITHM, expirationClaimIsRequired: true);

        self::expectException(ParsingTokenFailedException::class);
        $parser->payloadFromRefreshToken($refreshToken);
    }

    public function testItFailsToParseAnExpiredToken(): void
    {
        $payload      = ['user' => 'user', 'salt' => 'salt', 'exp' => strtotime('-1 minute')];
        $refreshToken = JWT::encode($payload, self::SECRET_KEY, self::ALGORITHM);

        $parser = new RefreshTokenParserUsingFirebase(self::SECRET_KEY, self::ALGORITHM, expirationClaimIsRequired: true);

        self::expectException(ParsingTokenFailedException::class);
        $parser->payloadFromRefreshToken($refreshToken);
    }
}
