<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Domain\View;

use JsonException;
use Kishlin\Backend\Account\Domain\View\JsonableSimpleAuthentication;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Domain\View\JsonableSimpleAuthentication
 */
final class JsonableSimpleAuthenticationTest extends TestCase
{
    public function testItCanBeCreatedFromScalars(): void
    {
        $view = JsonableSimpleAuthentication::fromScalars('token');

        self::assertInstanceOf(JsonableSimpleAuthentication::class, $view);
    }

    /**
     * @depends testItCanBeCreatedFromScalars
     *
     * @throws JsonException
     */
    public function testItCanBeConvertedToJson(): void
    {
        $view = JsonableSimpleAuthentication::fromScalars('token');

        self::assertSame(self::json(), $view->toJson());
    }

    private static function json(): string
    {
        return <<<'JSON'
{"token":"token"}
JSON;
    }
}
