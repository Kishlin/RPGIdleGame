<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Infrastructure;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Kishlin\Backend\Account\Infrastructure\AuthenticationGeneratorUsingFirebase;
use Kishlin\Backend\Shared\Infrastructure\Security\JWTGeneratorUsingFirebase;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class AuthenticationGeneratorUsingFirebaseTest extends TestCase
{
    private const SECRET_KEY = 'ThisKeyIsNotSoSecretButItIsTests';
    private const HOSTNAME   = 'test.rpgidlegame.com';
    private const ALGORITHM  = 'HS256';

    public function testItCanCreateAToken(): void
    {
        $jwtGenerator = new JWTGeneratorUsingFirebase(self::SECRET_KEY, self::HOSTNAME, self::ALGORITHM);

        $subjectUnderTest = new AuthenticationGeneratorUsingFirebase($jwtGenerator, false);

        self::assertIsString($subjectUnderTest->generateToken('uuid'));
    }

    public function testItCanCreateARefreshToken(): void
    {
        $jwtGenerator = new JWTGeneratorUsingFirebase(self::SECRET_KEY, self::HOSTNAME, self::ALGORITHM);

        $subjectUnderTest = new AuthenticationGeneratorUsingFirebase($jwtGenerator, false);

        self::assertIsString($subjectUnderTest->generateRefreshToken(userId: 'uuid', salt: 'salt'));
    }

    public function testItMustHaveExpirationValuesWhenTheExpClaimIsRequired(): void
    {
        $jwtGenerator = new JWTGeneratorUsingFirebase(self::SECRET_KEY, self::HOSTNAME, self::ALGORITHM);

        self::expectException(\InvalidArgumentException::class);
        new AuthenticationGeneratorUsingFirebase($jwtGenerator, true, '', '');
    }

    public function testItAddsTheExpClaimWhenItIsRequired(): void
    {
        $jwtGenerator = new JWTGeneratorUsingFirebase(self::SECRET_KEY, self::HOSTNAME, self::ALGORITHM);

        $subjectUnderTest = new AuthenticationGeneratorUsingFirebase($jwtGenerator, true, '+10 minute', '+1 month');

        $token = $subjectUnderTest->generateToken('uuid');

        $parsed = (array) JWT::decode($token, new Key(self::SECRET_KEY, self::ALGORITHM));

        self::assertArrayHasKey('exp', $parsed);
    }
}
