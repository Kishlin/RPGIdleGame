<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\Bus\Message;

use Kishlin\Backend\Shared\Domain\Bus\Message\Mapping;
use Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\Bus\Message\Constraint\ItMapsTheValueCorrectlyConstraint;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\Bus\Message\Mapping
 */
final class MappingTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testItMapsCorrectlyAMissingValue(): void
    {
        $source = [];
        $key    = 'missing-key';
        $object = $this->mappableObject();

        self::assertNull(ReflectionHelper::invoke($object, 'getIntOrNull', $source, $key));
        self::assertNull(ReflectionHelper::invoke($object, 'getBoolOrNull', $source, $key));
        self::assertNull(ReflectionHelper::invoke($object, 'getStringOrNull', $source, $key));

        self::assertSame(0, ReflectionHelper::invoke($object, 'getInt', $source, $key));
        self::assertSame('', ReflectionHelper::invoke($object, 'getString', $source, $key));
        self::assertFalse(ReflectionHelper::invoke($object, 'getBool', $source, $key));
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanRetrieveAValue(): void
    {
        $stringKey = 'key-string';
        $boolKey   = 'key-bool';
        $intKey    = 'key-int';

        $source = [
            $stringKey => '1',
            $boolKey   => true,
            $intKey    => 2,
        ];

        $object = $this->mappableObject();

        self::assertItMapsTheValueCorrectly(
            expectedTypedValue: 2,
            actual: ReflectionHelper::invoke($object, 'getInt', $source, $intKey),
        );

        self::assertItMapsTheValueCorrectly(
            expectedTypedValue: 2,
            actual: ReflectionHelper::invoke($object, 'getIntOrNull', $source, $intKey),
        );

        self::assertItMapsTheValueCorrectly(
            expectedTypedValue: '1',
            actual: ReflectionHelper::invoke($object, 'getString', $source, $stringKey),
        );

        self::assertItMapsTheValueCorrectly(
            expectedTypedValue: '1',
            actual: ReflectionHelper::invoke($object, 'getStringOrNull', $source, $stringKey),
        );

        self::assertItMapsTheValueCorrectly(
            expectedTypedValue: true,
            actual: ReflectionHelper::invoke($object, 'getBool', $source, $boolKey),
        );

        self::assertItMapsTheValueCorrectly(
            expectedTypedValue: true,
            actual: ReflectionHelper::invoke($object, 'getBoolOrNull', $source, $boolKey),
        );
    }

    public static function assertItMapsTheValueCorrectly(int|string|bool $expectedTypedValue, mixed $actual): void
    {
        self::assertThat($actual, new ItMapsTheValueCorrectlyConstraint($expectedTypedValue));
    }

    private function mappableObject(): object
    {
        return new class() {
            use Mapping;
        };
    }
}
