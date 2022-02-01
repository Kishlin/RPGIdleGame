<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Kishlin\Backend\Shared\Domain\Tools;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use ReflectionException;

abstract class AbstractNullableUuidType extends StringType
{
    /**
     * @throws ReflectionException
     */
    public function getName(): string
    {
        $shortClassName = Tools::shortClassName($this->mappedClass());

        return Tools::fromPascalToSnakeCase($shortClassName);
    }

    /**
     * @param ?string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): NullableUuidValueObject
    {
        $className = $this->mappedClass();

        return new $className($value);
    }

    /**
     * @param NullableUuidValueObject $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value->value();
    }

    /**
     * @return class-string<NullableUuidValueObject>
     */
    abstract protected function mappedClass(): string;
}
