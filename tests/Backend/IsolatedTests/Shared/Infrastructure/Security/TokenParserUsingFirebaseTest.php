<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Infrastructure\Security;

use Firebase\JWT\JWT;
use Kishlin\Backend\Shared\Domain\Security\ParsingTokenFailedException;
use Kishlin\Backend\Shared\Domain\Security\TokenPayload;
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
        $parser = new TokenParserUsingFirebase(self::SECRET_KEY, self::ALGORITHM, true);

        $token = JWT::encode(['user' => $userId, 'exp' => strtotime('+10 minute')], self::SECRET_KEY, self::ALGORITHM);

        $out = $parser->payloadFromToken($token);

        self::assertSame($userId, $out->userId());
    }

    public function testItParsesATokenWithoutExpirationWhenItIsNotRequired(): void
    {
        $payload = ['user' => 'user', 'exp' => strtotime('+1 minute')];

        $refreshToken = JWT::encode($payload, self::SECRET_KEY, self::ALGORITHM);

        $parser = new TokenParserUsingFirebase(self::SECRET_KEY, self::ALGORITHM, expirationClaimIsRequired: false);

        self::assertInstanceOf(TokenPayload::class, $parser->payloadFromToken($refreshToken));
    }

    public function testItFailsToParseATokenWithoutExpirationWhenItIsRequired(): void
    {
        $refreshToken = JWT::encode(['user' => 'user'], self::SECRET_KEY, self::ALGORITHM);

        $parser = new TokenParserUsingFirebase(self::SECRET_KEY, self::ALGORITHM, expirationClaimIsRequired: true);

        self::expectException(ParsingTokenFailedException::class);
        $parser->payloadFromToken($refreshToken);
    }

    public function testItFailsToParseAnExpiredToken(): void
    {
        $payload      = ['user' => 'user', 'exp' => strtotime('-1 minute')];
        $refreshToken = JWT::encode($payload, self::SECRET_KEY, self::ALGORITHM);

        $parser = new TokenParserUsingFirebase(self::SECRET_KEY, self::ALGORITHM, expirationClaimIsRequired: true);

        self::expectException(ParsingTokenFailedException::class);
        $parser->payloadFromToken($refreshToken);
    }
}
