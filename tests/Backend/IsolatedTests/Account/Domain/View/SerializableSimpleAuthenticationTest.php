<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Domain\View;

use Kishlin\Backend\Account\Domain\View\SerializableSimpleAuthentication;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Domain\View\SerializableSimpleAuthentication
 */
final class SerializableSimpleAuthenticationTest extends TestCase
{
    public function testItCanBeCreatedFromScalars(): void
    {
        $view = SerializableSimpleAuthentication::fromScalars('token');

        self::assertInstanceOf(SerializableSimpleAuthentication::class, $view);
    }

    /**
     * @depends testItCanBeCreatedFromScalars
     */
    public function testItCanBeSerialized(): void
    {
        $view = SerializableSimpleAuthentication::fromScalars('token');

        self::assertSame(self::serialized(), serialize($view));
    }

    public function testItCanBeUnserialized(): void
    {
        self::assertInstanceOf(SerializableSimpleAuthentication::class, unserialize($this->serialized()));
    }

    private static function serialized(): string
    {
        return <<<'TXT'
O:68:"Kishlin\Backend\Account\Domain\View\SerializableSimpleAuthentication":1:{s:5:"token";s:5:"token";}
TXT;
    }
}
