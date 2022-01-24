<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Isolated;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractUuidType;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * Abstract TestCase for custom DbalTypes mapping a \Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject.
 *
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractUuidType
 */
abstract class UuidTypeIsolatedTestCase extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function assertTypeNameIs(string $expected, AbstractUuidType $type): void
    {
        self::assertSame($expected, $type->getName());
    }

    public function assertItIsConvertedToDatabaseValue(string $uuid, UuidValueObject $valueObject, AbstractUuidType $type): void
    {
        $converted = $type->convertToDatabaseValue($valueObject, $this->platform());

        self::assertSame($uuid, $converted);
    }

    /**
     * @param class-string<object> $expectedClass
     */
    public function assertIsConvertedToPhpValue(string $expectedClass, string $uuid, AbstractUuidType $type): void
    {
        $converted = $type->convertToPHPValue($uuid, $this->platform());

        self::assertInstanceOf($expectedClass, $converted);
        self::assertSame($uuid, $converted->value());
    }

    /**
     * @return AbstractPlatform
     */
    private function platform(): object
    {
        return $this->getMockForAbstractClass(AbstractPlatform::class);
    }
}
