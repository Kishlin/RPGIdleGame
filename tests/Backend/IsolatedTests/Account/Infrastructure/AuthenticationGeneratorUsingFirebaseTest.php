<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Infrastructure;

use Kishlin\Backend\Account\Infrastructure\AuthenticationGeneratorUsingFirebase;
use Kishlin\Backend\Shared\Infrastructure\Security\JWTGeneratorUsingFirebase;
use PHPUnit\Framework\TestCase;

final class AuthenticationGeneratorUsingFirebaseTest extends TestCase
{
    private const SECRET_KEY = 'ThisKeyIsNotSoSecretButItIsTests';
    private const HOSTNAME   = 'test.rpgidlegame.com';
    private const ALGORITHM  = 'HS256';

    public function testItCanCreateAToken(): void
    {
        $jwtGenerator = new JWTGeneratorUsingFirebase(self::SECRET_KEY, self::HOSTNAME, self::ALGORITHM);

        $subjectUnderTest = new AuthenticationGeneratorUsingFirebase($jwtGenerator);

        self::assertIsString($subjectUnderTest->generateToken('uuid'));
    }

    public function testItCanCreateARefreshToken(): void
    {
        $jwtGenerator = new JWTGeneratorUsingFirebase(self::SECRET_KEY, self::HOSTNAME, self::ALGORITHM);

        $subjectUnderTest = new AuthenticationGeneratorUsingFirebase($jwtGenerator);

        self::assertIsString($subjectUnderTest->generateRefreshToken(userId: 'uuid', salt: 'salt'));
    }
}
