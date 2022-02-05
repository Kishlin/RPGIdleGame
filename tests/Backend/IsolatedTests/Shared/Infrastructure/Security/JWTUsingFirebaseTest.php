<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Infrastructure\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Kishlin\Backend\Shared\Infrastructure\Security\JWTGeneratorUsingFirebase;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Security\JWTGeneratorUsingFirebase
 */
final class JWTUsingFirebaseTest extends TestCase
{
    private const SECRET_KEY = 'ThisKeyIsNotSoSecretButItIsTests';
    private const HOSTNAME   = 'test.rpgidlegame.com';
    private const ALGORITHM  = 'HS256';

    public function testItCanGenerateAValidJWT(): void
    {
        $generator = new JWTGeneratorUsingFirebase(self::SECRET_KEY, self::HOSTNAME, self::ALGORITHM);

        $token = $generator->token([
            'test' => true,
            'exp' => strtotime('+10 minute'),
        ]);

        $payload = (array) JWT::decode($token, new Key(self::SECRET_KEY, self::ALGORITHM));

        self::assertArrayHasKey('test', $payload);
        self::assertTrue($payload['test']);
    }
}
