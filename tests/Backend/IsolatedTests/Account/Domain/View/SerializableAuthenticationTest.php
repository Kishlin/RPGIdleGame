<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Domain\View;

use Kishlin\Backend\Account\Domain\View\SerializableAuthentication;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Domain\View\SerializableAuthentication
 */
final class SerializableAuthenticationTest extends TestCase
{
    public function testItCanBeCreatedFromSource(): void
    {
        $view = SerializableAuthentication::fromScalars('token', 'refreshToken');

        self::assertInstanceOf(SerializableAuthentication::class, $view);
    }

    /**
     * @depends testItCanBeCreatedFromSource
     */
    public function testItCanBeSerialized(): void
    {
        $view = SerializableAuthentication::fromScalars('token', 'refreshToken');

        self::assertSame(self::serialized(), serialize($view));
    }

    public function testItCanBeUnserialized(): void
    {
        self::assertInstanceOf(SerializableAuthentication::class, unserialize($this->serialized()));
    }

    private static function serialized(): string
    {
        return <<<'TXT'
O:62:"Kishlin\Backend\Account\Domain\View\SerializableAuthentication":2:{s:5:"token";s:5:"token";s:12:"refreshToken";s:12:"refreshToken";}
TXT;
    }
}
