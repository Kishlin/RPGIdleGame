<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Domain\View;

use JsonException;
use Kishlin\Backend\Account\Domain\View\JsonableAuthentication;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Domain\View\JsonableAuthentication
 */
final class JsonableAuthenticationTest extends TestCase
{
    public function testItCanBeCreatedFromScalars(): void
    {
        $view = JsonableAuthentication::fromScalars('token', 'refreshToken');

        self::assertInstanceOf(JsonableAuthentication::class, $view);
    }

    /**
     * @depends testItCanBeCreatedFromScalars
     *
     * @throws JsonException
     */
    public function testItCanBeConvertedToJson(): void
    {
        $view = JsonableAuthentication::fromScalars('token', 'refreshToken');

        self::assertSame(self::json(), $view->toJson());
    }

    private static function json(): string
    {
        return <<<'JSON'
{"token":"token","refreshToken":"refreshToken"}
JSON;
    }
}
