<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain;

use Kishlin\Backend\Shared\Domain\Tools;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\Tools
 */
final class ToolsTest extends TestCase
{
    public function testStartsWith(): void
    {
        self::assertTrue(Tools::startsWith('123456789', ''));
        self::assertTrue(Tools::startsWith('123456789', '1'));
        self::assertTrue(Tools::startsWith('123456789', '123'));
        self::assertTrue(Tools::startsWith('123456789', '123456789'));

        self::assertFalse(Tools::startsWith('123456789', '9'));
        self::assertFalse(Tools::startsWith('123456789', 'a'));
    }

    public function testEndsWith(): void
    {
        self::assertTrue(Tools::endsWith('123456789', ''));
        self::assertTrue(Tools::endsWith('123456789', '9'));
        self::assertTrue(Tools::endsWith('123456789', '789'));
        self::assertTrue(Tools::endsWith('123456789', '123456789'));

        self::assertFalse(Tools::endsWith('123456789', '1'));
        self::assertFalse(Tools::endsWith('123456789', '0'));
    }

    public function testFromPascalToSnakeCaseTest(): void
    {
        self::assertSame('string', Tools::fromPascalToSnakeCase('string'));

        self::assertSame('short_string', Tools::fromPascalToSnakeCase('shortString'));

        self::assertSame('this_is_a_long_string', Tools::fromPascalToSnakeCase('ThisIsALongString'));
    }

    /**
     * @throws ReflectionException
     */
    public function testShortClassName(): void
    {
        self::assertSame('ToolsTest', Tools::shortClassName(self::class));
        self::assertSame('ToolsTest', Tools::shortClassName($this));

        self::expectException(ReflectionException::class);

        // @phpstan-ignore-next-line
        Tools::shortClassName('invalid');
    }
}
